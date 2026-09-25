/** Behaviour for the public site. Icons are rendered by app.js. */

/* ─── FAQ accordion ──────────────────────────────────────────────────────── */

const faqButtons = Array.from(document.querySelectorAll('[data-faq-toggle]'));

const setFaq = (button, open) => {
    button.setAttribute('aria-expanded', String(open));
    button.nextElementSibling.classList.toggle('open', open);
    button.querySelector('[data-faq-plus]').classList.toggle('hidden', open);
    button.querySelector('[data-faq-minus]').classList.toggle('hidden', ! open);
};

faqButtons.forEach((button, index) => {
    // The first answer starts open, matching the design.
    setFaq(button, index === 0);

    button.addEventListener('click', () => {
        const open = button.getAttribute('aria-expanded') === 'true';

        faqButtons.forEach((other) => setFaq(other, ! open && other === button));
    });
});

/* ─── Brand Blueprint discovery wizard ───────────────────────────────────── */

const form = document.querySelector('[data-blueprint-form]');

if (form) {
    const steps = Array.from(form.querySelectorAll('[data-step]'));
    const backButton = form.querySelector('[data-step-back]');
    const nextButton = form.querySelector('[data-step-next]');
    const nextLabel = form.querySelector('[data-step-next-label]');
    const segments = Array.from(form.querySelectorAll('[data-progress-segment]'));
    const stepCounter = form.querySelector('[data-step-counter]');
    const stepName = form.querySelector('[data-step-name]');
    const errorBox = form.querySelector('[data-form-error]');

    const formStage = document.querySelector('[data-stage="form"]');
    const loadingStage = document.querySelector('[data-stage="loading"]');

    let current = 0;

    /** The value currently entered for a step, or an empty string. */
    const valueOf = (step) => {
        const field = step.querySelector('input[type="radio"]:checked, input[type="text"], textarea');

        return field ? field.value.trim() : '';
    };

    const syncNextButton = () => {
        nextButton.disabled = valueOf(steps[current]) === '';
    };

    const showStep = (index) => {
        current = index;

        steps.forEach((step, i) => step.classList.toggle('step-current', i === index));

        segments.forEach((segment, i) => {
            segment.classList.toggle('bg-navy', i <= index);
            segment.classList.toggle('bg-navy/10', i > index);
        });

        stepCounter.textContent = `Step ${index + 1} of ${steps.length}`;
        stepName.textContent = steps[index].dataset.label;

        backButton.disabled = index === 0;
        nextLabel.textContent = index === steps.length - 1
            ? 'Generate Brand Blueprint™'
            : 'Continue';

        syncNextButton();

        const focusable = steps[index].querySelector('input[type="text"], textarea');
        window.setTimeout(() => focusable?.focus(), 50);
    };

    form.addEventListener('input', syncNextButton);
    form.addEventListener('change', syncNextButton);

    backButton.addEventListener('click', () => {
        if (current > 0) {
            showStep(current - 1);
        }
    });

    nextButton.addEventListener('click', () => {
        if (valueOf(steps[current]) === '') {
            return;
        }

        if (current < steps.length - 1) {
            showStep(current + 1);

            return;
        }

        form.requestSubmit();
    });

    // Enter advances a single-line step rather than submitting a half-filled form.
    form.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && event.target.matches('input[type="text"]')) {
            event.preventDefault();
            nextButton.click();
        }
    });

    /* ── Generating stage ── */

    const minimumMs = Number(form.dataset.minimumMs) || 2800;
    const loadingSteps = Array.from(document.querySelectorAll('[data-loading-step]'));
    const progressFill = document.querySelector('[data-progress-fill]');
    const progressPct = document.querySelector('[data-progress-pct]');

    const paintLoadingSteps = (activeIndex) => {
        loadingSteps.forEach((step, i) => {
            const state = i < activeIndex ? 'done' : i === activeIndex ? 'active' : 'pending';

            step.classList.toggle('bg-steel/40', state === 'active');

            step.querySelectorAll('[data-state]').forEach((marker) => {
                marker.classList.toggle('hidden', marker.dataset.state !== state);
            });

            step.querySelector('[data-step-label]').className = {
                done: 'text-navy',
                active: 'font-medium text-navy',
                pending: 'text-ink/50',
            }[state];
        });
    };

    const dashboardUrl = form.dataset.dashboardUrl;

    /**
     * @param {string} message
     * @param {boolean} maybeGenerated  The server may have finished anyway — the
     *   connection dropped, it did not report a failure. Sending someone back to
     *   "try again" here would spend a second blueprint from their allowance.
     */
    const showError = (message, maybeGenerated = false) => {
        loadingStage.style.display = 'none';
        formStage.style.display = 'block';

        errorBox.replaceChildren(document.createTextNode(message));

        if (maybeGenerated && dashboardUrl) {
            errorBox.append(' ');

            const link = document.createElement('a');
            link.href = dashboardUrl;
            link.textContent = 'Check your dashboard';
            link.className = 'font-medium underline underline-offset-2';
            errorBox.append(link, ' before trying again.');
        }

        errorBox.classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        errorBox.classList.add('hidden');
        formStage.style.display = 'none';
        loadingStage.style.display = 'block';

        document.querySelectorAll('[data-loading-brand-name]').forEach((el) => {
            el.textContent = form.querySelector('[name="brand_name"]').value.trim() || 'your brand';
        });

        paintLoadingSteps(0);

        let resultUrl = null;
        let failure = null;

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(async (response) => {
                const data = await response.json().catch(() => ({}));

                if (! response.ok) {
                    // A message means the app rejected it on purpose — out of
                    // allowance, invalid answers — and retrying is the right advice.
                    // No message means a gateway or proxy gave up while the server
                    // was very likely still working.
                    throw Object.assign(
                        new Error(data.message || 'The connection dropped while your blueprint was being generated.'),
                        { maybeGenerated: ! data.message },
                    );
                }

                resultUrl = data.url;
            })
            .catch((error) => {
                failure = {
                    message: error.message || 'The connection dropped while your blueprint was being generated.',
                    // A rejected fetch means the browser never heard back at all.
                    maybeGenerated: error.maybeGenerated ?? true,
                };
            });

        const start = performance.now();

        const tick = () => {
            if (failure) {
                showError(failure.message, failure.maybeGenerated);

                return;
            }

            const elapsed = performance.now() - start;

            // Hold just short of full until the blueprint actually comes back.
            const ceiling = resultUrl ? 100 : 95;
            const percent = Math.min(ceiling, (elapsed / minimumMs) * 100);

            progressFill.style.width = `${percent}%`;
            progressPct.textContent = `${Math.round(percent)}%`;
            paintLoadingSteps(
                Math.min(loadingSteps.length - 1, Math.floor((percent / 100) * loadingSteps.length)),
            );

            if (percent >= 100 && resultUrl) {
                window.setTimeout(() => { window.location.href = resultUrl; }, 300);

                return;
            }

            window.requestAnimationFrame(tick);
        };

        window.requestAnimationFrame(tick);
    });

    showStep(0);
}

/* ─── Motion ─────────────────────────────────────────────────────────────── */

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Reveal elements as they scroll into view, staggering the children of a group
 * so grids arrive as a wave rather than all at once.
 */
const initReveal = () => {
    const targets = document.querySelectorAll('[data-reveal]');

    if (! targets.length) {
        return;
    }

    if (reducedMotion || ! ('IntersectionObserver' in window)) {
        targets.forEach((el) => el.classList.add('is-visible'));

        return;
    }

    document.querySelectorAll('[data-reveal-group]').forEach((group) => {
        group.querySelectorAll(':scope > [data-reveal]').forEach((el, index) => {
            el.style.transitionDelay = `${Math.min(index, 7) * 70}ms`;
        });
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (! entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.1 });

    targets.forEach((el) => observer.observe(el));
};

/**
 * Fill the meters and count the figures up the first time a panel is reached.
 */
const initFigures = () => {
    const panels = document.querySelectorAll('[data-figures]');

    if (! panels.length) {
        return;
    }

    const countUp = (el) => {
        const target = Number(el.dataset.count);
        const decimals = Number(el.dataset.countDecimals ?? 0);
        const prefix = el.dataset.countPrefix ?? '';
        const suffix = el.dataset.countSuffix ?? '';
        const format = (value) => prefix + value.toFixed(decimals) + suffix;

        if (reducedMotion || Number.isNaN(target)) {
            el.textContent = format(target);

            return;
        }

        const duration = 1100;
        const start = performance.now();

        const tick = (now) => {
            const progress = Math.min(1, (now - start) / duration);
            // Ease out, so the number decelerates into its final value.
            el.textContent = format(target * (1 - Math.pow(1 - progress, 3)));

            if (progress < 1) {
                window.requestAnimationFrame(tick);
            }
        };

        el.textContent = format(0);
        window.requestAnimationFrame(tick);
    };

    const play = (panel) => {
        panel.querySelectorAll('[data-bar], [data-bar-height]').forEach((bar) => {
            bar.classList.add('is-filled');
        });

        panel.querySelectorAll('[data-count]').forEach(countUp);
    };

    if (! ('IntersectionObserver' in window)) {
        panels.forEach(play);

        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (! entry.isIntersecting) {
                return;
            }

            play(entry.target);
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.25 });

    panels.forEach((panel) => observer.observe(panel));
};

/**
 * Mark the link whose section is currently being read. Used by both the home
 * page nav and the contents list on the legal pages.
 */
const spySections = (links, key, { clearNearTop = false } = {}) => {
    if (! links.length || ! ('IntersectionObserver' in window)) {
        return;
    }

    const sections = links
        .map((link) => document.getElementById(link.dataset[key]))
        .filter(Boolean);

    if (! sections.length) {
        return;
    }

    const visible = new Set();

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            entry.isIntersecting ? visible.add(entry.target.id) : visible.delete(entry.target.id);
        });

        // With several sections on screen, the highest one wins.
        const current = sections.find((section) => visible.has(section.id));

        if (! current) {
            // Back up at the top: nothing is being read yet.
            if (clearNearTop && window.scrollY < window.innerHeight * 0.5) {
                links.forEach((link) => link.classList.remove('is-current'));
            }

            // Otherwise we are between two sections — hold the last one rather
            // than blinking the marker off.
            return;
        }

        links.forEach((link) => {
            link.classList.toggle('is-current', link.dataset[key] === current.id);
        });
    }, { rootMargin: '-20% 0px -65% 0px' });

    sections.forEach((section) => observer.observe(section));
};

/** Lift the header once the page is scrolled, and track the current section. */
const initHeader = () => {
    const header = document.querySelector('[data-site-header]');

    if (header) {
        const sync = () => header.classList.toggle('is-scrolled', window.scrollY > 8);

        sync();
        window.addEventListener('scroll', sync, { passive: true });
    }

    spySections(Array.from(document.querySelectorAll('[data-nav-link]')), 'navLink', {
        clearNearTop: true,
    });

    spySections(Array.from(document.querySelectorAll('[data-toc-link]')), 'tocLink');
};

/** Show how far through a long document you are. */
const initReadingProgress = () => {
    const bar = document.querySelector('[data-reading-progress]');

    if (! bar) {
        return;
    }

    const sync = () => {
        const scrollable = document.documentElement.scrollHeight - window.innerHeight;

        bar.style.width = scrollable > 0
            ? `${Math.min(100, (window.scrollY / scrollable) * 100)}%`
            : '0%';
    };

    sync();
    window.addEventListener('scroll', sync, { passive: true });
    window.addEventListener('resize', sync);
};

/** Offer a way back up once the document is long behind you. */
const initBackToTop = () => {
    const button = document.querySelector('[data-back-to-top]');

    if (! button) {
        return;
    }

    const sync = () => {
        const show = window.scrollY > window.innerHeight;

        button.classList.toggle('opacity-0', ! show);
        button.classList.toggle('translate-y-3', ! show);
        button.classList.toggle('pointer-events-none', ! show);
    };

    sync();
    window.addEventListener('scroll', sync, { passive: true });

    button.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: reducedMotion ? 'auto' : 'smooth' });
    });
};

/** Put a form's submit button into a pending state so a slow post feels handled. */
const initSubmitStates = () => {
    document.querySelectorAll('form[data-submitting]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');

            if (! button) {
                return;
            }

            button.disabled = true;
            button.querySelector('[data-submitting-spinner]')?.classList.remove('hidden');

            const label = button.querySelector('[data-submitting-label]');

            if (label) {
                label.textContent = 'Sending…';
            }
        });
    });
};

/** Count what has been typed into a length-limited field. */
const initCharCount = () => {
    const input = document.querySelector('[data-char-input]');
    const output = document.querySelector('[data-char-count]');

    if (! input || ! output) {
        return;
    }

    const sync = () => { output.textContent = input.value.length; };

    sync();
    input.addEventListener('input', sync);
};

initReveal();
initFigures();
initHeader();
initReadingProgress();
initBackToTop();
initSubmitStates();
initCharCount();
