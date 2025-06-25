<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';

</script>

<template>
    <!-- Footer -->
    <footer class="bg-gray-900 py-12 text-white">
        <div class="container mx-auto px-6">
            <div class="grid gap-8 md:grid-cols-4">
                <div>
                    <div class="mb-4 flex items-center space-x-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-indigo-400"
                        >
                            <path d="M12 2v4"></path>
                            <path d="m16 6-4-4-4 4"></path>
                            <path d="M18 20a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2"></path>
                            <path d="m6 16-4-4 4-4"></path>
                            <path d="m18 8 4-4-4-4"></path>
                        </svg>
                        <span class="text-xl font-bold">E-Track</span>
                    </div>
                    <p class="text-gray-400">Beautiful expense tracking for everyone. Take control of your finances today.</p>
                </div>
                <div>
                    <h3 class="mb-4 text-lg font-semibold">Product</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 transition hover:text-white">Features</a></li>
                        <li><a href="#" class="text-gray-400 transition hover:text-white">Pricing</a></li>
                        <li><a href="#" class="text-gray-400 transition hover:text-white">Examples</a></li>
                        <li><a href="#" class="text-gray-400 transition hover:text-white">Updates</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 text-lg font-semibold">Company</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 transition hover:text-white">About</a></li>
                        <li><a href="#" class="text-gray-400 transition hover:text-white">Blog</a></li>
                        <li><a href="#" class="text-gray-400 transition hover:text-white">Careers</a></li>
                        <li><a href="#" class="text-gray-400 transition hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 text-lg font-semibold">Subscribe</h3>
                    <p class="mb-4 text-gray-400">Get the latest updates and news</p>
                    <div class="flex">
                        <Input placeholder="Your email" class="rounded-r-none border-gray-700 bg-gray-800 text-white" />
                        <Button class="rounded-l-none bg-indigo-600 hover:bg-indigo-700 text-white">Subscribe</Button>
                    </div>
                </div>
            </div>
            <div class="mt-12 border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>© {{ new Date().getFullYear() }} E-Track. All rights reserved.</p>
            </div>
        </div>
    </footer>
</template>
