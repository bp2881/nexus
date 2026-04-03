<?php // includes/footer.php ?>
</main>
<!-- Footer -->
<footer class="w-full border-t border-white/5 bg-[#121316] relative z-20">
    <div class="flex flex-col md:flex-row justify-between items-center px-12 py-10 w-full max-w-screen-2xl mx-auto">
        <div class="flex flex-col gap-2 mb-8 md:mb-0">
            <div class="flex items-center gap-3">
                <img src="/assets/images/nexus.png" alt="Nexus Logo" class="h-8 w-8 rounded-full object-cover">
                <div class="text-xl font-bold text-indigo-100 font-headline">Nexus</div>
            </div>
            <div class="font-['Inter'] text-sm tracking-wide text-slate-500">&copy; <?= date('Y')?> Nexus Club. All
rights reserved.</div>
<div class="font-['Inter'] text-sm tracking-wide text-slate-400 mt-1">Contact: Pranav Bairy | Ph: +91 98765 43210</div>
</div>
<div class="flex items-center gap-10">
    <a href="/index.php"
        class="font-['Inter'] text-sm tracking-wide text-slate-500 hover:text-indigo-300 transition-colors">Home</a>
    <a href="/pages/events.php"
        class="font-['Inter'] text-sm tracking-wide text-slate-500 hover:text-indigo-300 transition-colors">Events</a>
    <a href="/pages/projects.php"
        class="font-['Inter'] text-sm tracking-wide text-slate-500 hover:text-indigo-300 transition-colors">Projects</a>
    <a href="/pages/contact.php"
        class="font-['Inter'] text-sm tracking-wide text-slate-500 hover:text-indigo-300 transition-colors">Contact
        Us</a>
</div>
<div class="flex items-center gap-6 mt-8 md:mt-0">
    <a href="#" class="text-slate-500 hover:text-primary transition-colors"><span
            class="material-symbols-outlined">analytics</span></a>
    <a href="#" class="text-slate-500 hover:text-primary transition-colors"><span
            class="material-symbols-outlined">api</span></a>
</div>
</div>
</footer>
<script type="module" src="/assets/js/main.js"></script>
</body>

</html>