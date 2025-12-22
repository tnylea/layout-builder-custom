import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


// 1. Select the iframe element
const iframe = document.querySelector('iframe');

// 2. Ensure the iframe has finished loading
iframe.onload = function() {
    // 3. Access the iframe's document
    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
    
    // 4. Inject HTML into the body
    iframeDoc.body.innerHTML = `<div class="min-h-screen grid grid-rows-[auto_1fr_auto]">
    <!-- Header -->
    <header class="border-b">
        <!-- Header slot -->
        <div data-ignore class="w-full h-[100px]"></div>
    </header>

    <!-- Main layout -->
    <div class="grid grid-cols-1 md:grid-cols-[16rem_1fr]">
        <!-- Sidebar -->
        <aside class="border-r">
            <!-- Sidebar slot -->
        </aside>

        <!-- Content -->
        <main class="p-6">
            <!-- Content slot -->
        </main>
    </div>

    <!-- Footer -->
    <footer class="border-t">
        <!-- Footer slot -->
        <div data-ignore class="w-full h-[100px]"></div>
    </footer>
</div>`;
};