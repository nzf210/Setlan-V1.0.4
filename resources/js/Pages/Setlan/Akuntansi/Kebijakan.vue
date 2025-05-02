<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import Akuntansi from "./Index.vue";
</script>

<template>

    <Head>
        <title>Kebijakan Akuntansi</title>
    </Head>
    <Akuntansi>
        <section class="text-gray-600 body-font">
            <div class="container px-5 py-24 mx-auto flex flex-wrap">
                <div class="flex flex-wrap w-full">
                    <div class="lg:w-2/5 md:w-1/2 md:pr-10 md:py-6">
                        <div class="flex relative pb-12">
                            <div class="h-full w-10 absolute inset-0 flex items-center justify-center">
                                <div class="h-full w-1 bg-gray-200 pointer-events-none"></div>
                            </div>
                            <div
                                class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-500 inline-flex items-center justify-center text-white relative z-10">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            </div>
                            <div class="flex-grow pl-4">
                                <h2 class="font-medium title-font text-sm text-gray-900 mb-1 tracking-wider">
                                    Kebijakan Akuntansi
                                </h2>
                                <p class="leading-relaxed">
                                    VHS cornhole pop-up, try-hard 8-bit iceland helvetica. Kinfolk bespoke
                                    try-hard cliche palo santo offal.
                                </p>
                            </div>
                        </div>
                        <div class="flex relative pb-12">
                            <div class="h-full w-10 absolute inset-0 flex items-center justify-center">
                                <div class="h-full w-1 bg-gray-200 pointer-events-none"></div>
                            </div>
                            <div
                                class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-500 inline-flex items-center justify-center text-white relative z-10">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                                </svg>
                            </div>
                            <div class="flex-grow pl-4">
                                <h2 class="font-medium title-font text-sm text-gray-900 mb-1 tracking-wider">
                                    STEP 2
                                </h2>
                                <p class="leading-relaxed">
                                    Vice migas literally kitsch +1 pok pok. Truffaut hot chicken slow-carb
                                    health goth, vape typewriter.
                                </p>
                            </div>
                        </div>
                        <div class="flex relative pb-12">
                            <div class="h-full w-10 absolute inset-0 flex items-center justify-center">
                                <div class="h-full w-1 bg-gray-200 pointer-events-none"></div>
                            </div>
                            <div
                                class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-500 inline-flex items-center justify-center text-white relative z-10">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                    <circle cx="12" cy="5" r="3"></circle>
                                    <path d="M12 22V8M5 12H2a10 10 0 0020 0h-3"></path>
                                </svg>
                            </div>
                            <div class="flex-grow pl-4">
                                <h2 class="font-medium title-font text-sm text-gray-900 mb-1 tracking-wider">
                                    STEP 3
                                </h2>
                                <p class="leading-relaxed">
                                    Coloring book nar whal glossier master cleanse umami. Salvia +1 master
                                    cleanse blog taiyaki.
                                </p>
                            </div>
                        </div>
                        <div class="flex relative pb-12">
                            <div class="h-full w-10 absolute inset-0 flex items-center justify-center">
                                <div class="h-full w-1 bg-gray-200 pointer-events-none"></div>
                            </div>
                        </div>
                        <div class="flex relative"></div>
                    </div>
                    <img class="lg:w-3/5 md:w-1/2 object-cover object-center rounded-lg md:mt-0 mt-12"
                        src="https://dummyimage.com/1200x500" alt="step" />
                </div>
            </div>
        </section>
    </Akuntansi>
</template>
