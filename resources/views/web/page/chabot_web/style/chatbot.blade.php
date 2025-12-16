
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
<link rel="shortcut icon" type="image/x-icon" href="{{ $faviconUrl }}">

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-in-out;
    }
    .bounce-dots > div {
        animation: bounce 1.4s infinite;
    }
    .bounce-dots > div:nth-child(2) {
        animation-delay: 0.1s;
    }
    .bounce-dots > div:nth-child(3) {
        animation-delay: 0.2s;
    }
    @keyframes bounce {
        0%, 80%, 100% {
            transform: translateY(0);
            opacity: 0.5;
        }
        40% {
            transform: translateY(-8px);
            opacity: 1;
        }
    }

    @keyframes gradientBackground {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    @keyframes bounce-custom {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-12px);
        }
    }

    @keyframes bounce-slow {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-8px);
        }
    }

    .gradient-animated {
        background: linear-gradient(-45deg, #2563eb, #2571ec, #2325b1, #0c4fe1);
        background-size: 400% 400%;
        animation: gradientBackground 15s ease infinite;
    }

    .animate-bounce-chatbot {
        animation: bounce-custom 6s infinite;
    }
</style>