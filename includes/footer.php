<?php // includes/footer.php ?>
<footer class="site-footer">
    <div class="footer-inner" style="grid-template-columns: 2fr 1.2fr 1fr 1fr;">
        <div class="footer-brand">
            <div class="footer-logo">
                <div class="nav-logo-icon"><img src="/assets/images/nexus.png" alt="NX"></div>
                <span class="nav-logo-text">Nexus</span>
            </div>
            <p>A student-run tech club where curious minds build real things, learn fast, and grow together.</p>
        </div>
        <div class="footer-col">
            <h4>Contact Us</h4>
            <ul style="color:var(--text-dim);line-height:1.8;">
                <li><span class="msi" style="font-size:16px;vertical-align:middle;margin-right:0.4rem;">call</span> +91 98765 43210</li>
                <li><span class="msi" style="font-size:16px;vertical-align:middle;margin-right:0.4rem;">mail</span> contact@nexusclub.in</li>
            </ul>
        </div>
        <div class="footer-col" style="flex:0.7">
            <h4>Navigate</h4>
            <ul>
                <li><a href="/index.php">Home</a></li>
                <li><a href="/pages/events.php">Events</a></li>
                <li><a href="/pages/projects.php">Projects</a></li>
                <li><a href="/pages/materials.php">Materials</a></li>
                <li><a href="/pages/gallery.php">Gallery</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Connect</h4>
            <ul>
                <li><a href="#">GitHub</a></li>
                <li><a href="#">LinkedIn</a></li>
                <li><a href="#">Discord</a></li>
                <li><a href="#">Instagram</a></li>
                <li><a href="/admin/">Admin</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> Nexus Club. Built with PHP &amp; SQLite.</p>
    </div>
</footer>
<script src="/assets/js/main.js"></script>
</body>
</html>
