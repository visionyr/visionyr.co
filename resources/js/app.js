/**
 * Shared bootstrap for every page: icons, plus the handful of one-liners the
 * layouts rely on. Page-specific behaviour lives in site.js and cms.js.
 */
import {
    createIcons,
    ArrowLeft,
    ArrowRight,
    ArrowUpRight,
    BookOpen,
    Briefcase,
    Building2,
    Calendar,
    CalendarDays,
    Check,
    ChevronLeft,
    ChevronRight,
    CircleAlert,
    CircleCheck,
    Coffee,
    Compass,
    Crown,
    Download,
    Droplets,
    Eye,
    EyeOff,
    FileText,
    Heart,
    Layers,
    LayoutDashboard,
    LineChart,
    LockKeyhole,
    LogOut,
    Mail,
    Megaphone,
    Menu,
    MessageSquare,
    Mic,
    Minus,
    Palette,
    Pencil,
    PenLine,
    Phone,
    Plus,
    Radar,
    RefreshCw,
    Rocket,
    Search,
    ShieldCheck,
    Shirt,
    Sparkles,
    Star,
    Tag,
    Target,
    Trash2,
    TrendingUp,
    Type,
    User,
    UserPlus,
    UserRound,
    Users,
    X,
    Zap,
} from 'lucide';

export const icons = {
    ArrowLeft,
    ArrowRight,
    ArrowUpRight,
    BookOpen,
    Briefcase,
    Building2,
    Calendar,
    CalendarDays,
    Check,
    ChevronLeft,
    ChevronRight,
    CircleAlert,
    CircleCheck,
    Coffee,
    Compass,
    Crown,
    Download,
    Droplets,
    Eye,
    EyeOff,
    FileText,
    Heart,
    Layers,
    LayoutDashboard,
    LineChart,
    LockKeyhole,
    LogOut,
    Mail,
    Megaphone,
    Menu,
    MessageSquare,
    Mic,
    Minus,
    Palette,
    Pencil,
    PenLine,
    Phone,
    Plus,
    Radar,
    RefreshCw,
    Rocket,
    Search,
    ShieldCheck,
    Shirt,
    Sparkles,
    Star,
    Tag,
    Target,
    Trash2,
    TrendingUp,
    Type,
    User,
    UserPlus,
    UserRound,
    Users,
    X,
    Zap,
};

/** Render every `data-lucide` placeholder currently in the document. */
export const renderIcons = () => createIcons({ icons });

renderIcons();

document.querySelectorAll('[data-current-year]').forEach((el) => {
    el.textContent = new Date().getFullYear();
});

/* Password visibility toggles */
document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.passwordToggle);

        if (! input) {
            return;
        }

        const reveal = input.type === 'password';

        input.type = reveal ? 'text' : 'password';
        button.querySelector('[data-icon-show]')?.classList.toggle('hidden', reveal);
        button.querySelector('[data-icon-hide]')?.classList.toggle('hidden', ! reveal);
    });
});
