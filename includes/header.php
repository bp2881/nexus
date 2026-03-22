<?php $current_page = basename($_SERVER['PHP_SELF'], '.php'); ?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' — Nexus' : 'Nexus' ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-surface-variant": "#c7c4d7",
                        "surface": "#121316",
                        "secondary-fixed": "#ffdf9e",
                        "secondary-fixed-dim": "#fabd00",
                        "on-error": "#690005",
                        "error": "#ffb4ab",
                        "on-secondary-fixed": "#261a00",
                        "primary-fixed": "#e1e0ff",
                        "surface-container-highest": "#343538",
                        "on-error-container": "#ffdad6",
                        "on-secondary-fixed-variant": "#5b4300",
                        "primary-container": "#8083ff",
                        "secondary": "#ffdf9e",
                        "outline-variant": "#464554",
                        "on-tertiary-fixed-variant": "#004493",
                        "primary": "#c0c1ff",
                        "on-primary-fixed-variant": "#2f2ebe",
                        "tertiary-fixed-dim": "#adc7ff",
                        "tertiary-container": "#4a8eff",
                        "on-surface": "#e3e2e6",
                        "on-tertiary": "#002e68",
                        "tertiary": "#adc7ff",
                        "surface-container": "#1e2022",
                        "on-background": "#e3e2e6",
                        "outline": "#908fa0",
                        "surface-bright": "#38393c",
                        "surface-container-high": "#292a2d",
                        "surface-dim": "#121316",
                        "inverse-on-surface": "#2f3033",
                        "on-secondary": "#3f2e00",
                        "tertiary-fixed": "#d8e2ff",
                        "error-container": "#93000a",
                        "inverse-primary": "#494bd6",
                        "on-secondary-container": "#6a4e00",
                        "on-primary": "#1000a9",
                        "surface-container-lowest": "#0d0e11",
                        "background": "#121316",
                        "on-tertiary-fixed": "#001a41",
                        "on-tertiary-container": "#00285c",
                        "primary-fixed-dim": "#c0c1ff",
                        "surface-container-low": "#1a1b1e",
                        "inverse-surface": "#e3e2e6",
                        "on-primary-fixed": "#07006c",
                        "surface-tint": "#c0c1ff",
                        "surface-variant": "#343538",
                        "secondary-container": "#fabd00",
                        "on-primary-container": "#0d0096"
                    },
                    fontFamily: {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    },
                    borderRadius: { "DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-panel { background: rgba(18, 19, 22, 0.6); backdrop-filter: blur(20px); }
        .gradient-text { background: linear-gradient(135deg, #c0c1ff 0%, #8083ff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .gold-gradient { background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .silver-gradient { background: linear-gradient(135deg, #E0E0E0 0%, #9E9E9E 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .bronze-gradient { background: linear-gradient(135deg, #CD7F32 0%, #8D6E63 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .rank-shadow-1 { text-shadow: 0 0 20px rgba(255, 215, 0, 0.4); }
        .rank-shadow-2 { text-shadow: 0 0 20px rgba(224, 224, 224, 0.4); }
        .rank-shadow-3 { text-shadow: 0 0 20px rgba(205, 127, 50, 0.4); }
    </style>
</head>
<body class="bg-surface text-on-surface font-body selection:bg-primary-container selection:text-on-primary-container">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 bg-[#121316]/80 backdrop-blur-xl border-b border-white/5 shadow-2xl shadow-indigo-500/5">
    <div class="flex justify-between items-center px-8 py-4 max-w-screen-2xl mx-auto">
        <div class="flex items-center gap-8">
            <div class="flex items-center gap-3">
                <img src="/assets/images/nexus.png" alt="Nexus Logo" class="h-8 w-8 rounded-full object-cover">
                <div class="text-2xl font-black tracking-tighter text-indigo-100 font-headline">Nexus</div>
            </div>
            <div class="hidden md:flex gap-6">
                <a class="text-sm font-bold <?= $current_page==='index' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-1' : 'text-outline hover:text-indigo-200 transition-colors' ?>" href="/index.php">Home</a>
                <a class="text-sm font-bold <?= $current_page==='events' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-1' : 'text-outline hover:text-indigo-200 transition-colors' ?>" href="/pages/events.php">Events</a>
                <a class="text-sm font-bold <?= $current_page==='projects' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-1' : 'text-outline hover:text-indigo-200 transition-colors' ?>" href="/pages/projects.php">Projects</a>
                <a class="text-sm font-bold <?= $current_page==='gallery' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-1' : 'text-outline hover:text-indigo-200 transition-colors' ?>" href="/pages/gallery.php">Gallery</a>
                <a class="text-sm font-bold <?= $current_page==='members' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-1' : 'text-outline hover:text-indigo-200 transition-colors' ?>" href="/pages/members.php">Members</a>
                <a class="text-sm font-bold <?= $current_page==='leaderboard' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-1' : 'text-outline hover:text-indigo-200 transition-colors' ?>" href="/pages/leaderboard.php">Leaderboard</a>
                <a class="text-sm font-bold <?= $current_page==='materials' ? 'text-indigo-400 border-b-2 border-indigo-400 pb-1' : 'text-outline hover:text-indigo-200 transition-colors' ?>" href="/pages/materials.php">Materials</a>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <a href="/pages/contact.php" class="bg-primary text-on-primary px-6 py-2 rounded-full font-bold text-sm hover:bg-primary-fixed transition-colors">Join Us</a>
        </div>
    </div>
</nav>
<main class="pt-32 pb-24 mx-auto w-full">
