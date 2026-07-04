<footer class="border-t border-border mt-12">
    <div class="max-w-screen-xl mx-auto px-4 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
            <div>
                <p class="font-black text-base text-foreground mb-2">PORTAL<span class="text-[#e53935]">BEASISWA</span></p>
                <p class="text-muted-foreground text-xs leading-relaxed">Platform informasi beasiswa terpercaya untuk pelajar Indonesia.</p>
            </div>
            <div>
                <p class="text-foreground font-semibold text-xs mb-3 uppercase tracking-wider">Navigasi</p>
                <ul class="space-y-2 text-xs text-muted-foreground">
                    <li><a href="{{ url('/home') }}" class="hover:text-[#e53935] transition-colors">Beranda</a></li>
                    <li><a href="{{ route('library') }}" class="hover:text-[#e53935] transition-colors">Katalog</a></li>
                    <li><a href="{{ route('dashboard') }}" class="hover:text-[#e53935] transition-colors">Dashboard</a></li>
                    <li><button type="button" id="btn-about" class="hover:text-[#e53935] transition-colors cursor-pointer text-left">Tentang Kami</button></li>
                </ul>
            </div>
            <div>
                <p class="text-foreground font-semibold text-xs mb-3 uppercase tracking-wider">Tingkat</p>
                <ul class="space-y-2 text-xs text-muted-foreground">
                    <li><a href="{{ route('library', ['level' => 's1']) }}" class="hover:text-[#e53935] transition-colors">S1 / Sarjana</a></li>
                    <li><a href="{{ route('library', ['level' => 's2']) }}" class="hover:text-[#e53935] transition-colors">S2 / Master</a></li>
                    <li><a href="{{ route('library', ['level' => 's3']) }}" class="hover:text-[#e53935] transition-colors">S3 / Doktor</a></li>
                </ul>
            </div>
            <div>
                <p class="text-foreground font-semibold text-xs mb-3 uppercase tracking-wider">Ikuti Kami</p>
                <ul class="space-y-2 text-xs text-muted-foreground">
                    <li><a href="#" class="hover:text-[#e53935] transition-colors">Instagram</a></li>
                    <li><a href="#" class="hover:text-[#e53935] transition-colors">Facebook</a></li>
                    <li><a href="#" class="hover:text-[#e53935] transition-colors">Telegram</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-border pt-4 text-center text-muted-foreground text-xs">
            © 2026 PortalBeasiswa. All rights reserved.
        </div>
    </div>
</footer>
