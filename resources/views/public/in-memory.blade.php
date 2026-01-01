<x-public-layout>
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4 text-blue-500">In Loving Memory</h1>
            <p class="text-xl text-indigo-100">This scholarship is dedicated to the memory of our beloved parents</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Introduction -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 mb-8">
            <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                This scholarship program was established in loving memory of our late parents, whose dedication to education and
                commitment to empowering the youth of Isan-Ekiti continues to inspire this initiative. Their legacy lives on
                through every student whose life is touched by this program.
            </p>
        </div>

        <!-- Memorial Photo Gallery Slideshow -->
        @if($photos->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8" x-data="{
            currentIndex: 0,
            photos: {{ json_encode($photos->map(fn($p) => ['path' => Storage::url($p->photo_path), 'caption' => $p->caption])) }},
            get currentPhoto() {
                return this.photos[this.currentIndex];
            },
            next() {
                this.currentIndex = (this.currentIndex + 1) % this.photos.length;
            },
            prev() {
                this.currentIndex = (this.currentIndex - 1 + this.photos.length) % this.photos.length;
            },
            goTo(index) {
                this.currentIndex = index;
            }
        }" x-init="setInterval(() => { next() }, 5000)">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-6">
                <h2 class="text-3xl font-bold text-blue-400">Memorial Gallery</h2>
            </div>

            <!-- Slideshow Container -->
            <div class="aspect-video bg-gray-900 relative overflow-hidden">
                <!-- Images -->
                <template x-for="(photo, index) in photos" :key="index">
                    <div x-show="currentIndex === index"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0">
                        <img :src="photo.path"
                             :alt="photo.caption || 'Memorial photo'"
                             class="w-full h-full object-contain">
                    </div>
                </template>

                <!-- Caption -->
                <div class="bg-gradient-to-t from-black/80 to-transparent absolute bottom-0 left-0 right-0 p-6 text-white" x-show="currentPhoto.caption">
                    <p class="text-lg text-center" x-text="currentPhoto.caption"></p>
                </div>

                <!-- Previous Button - Left Side -->
                <button @click="prev()"
                        style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);"
                        class="bg-white/90 dark:bg-gray-800/90 text-gray-800 dark:text-white p-3 rounded-full hover:bg-white dark:hover:bg-gray-700 transition-all shadow-lg z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Next Button - Right Side -->
                <button @click="next()"
                        style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);"
                        class="bg-white/90 dark:bg-gray-800/90 text-gray-800 dark:text-white p-3 rounded-full hover:bg-white dark:hover:bg-gray-700 transition-all shadow-lg z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <!-- Indicators -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                    <template x-for="(photo, index) in photos" :key="index">
                        <button @click="goTo(index)"
                                class="w-3 h-3 rounded-full transition-all"
                                :class="currentIndex === index ? 'bg-white scale-125' : 'bg-white/50 hover:bg-white/75'">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Counter -->
            <div class="p-4 bg-gray-50 dark:bg-gray-900 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <span x-text="currentIndex + 1"></span> / <span x-text="photos.length"></span>
                </p>
            </div>
        </div>
        @endif

        <!-- Mother's Memorial -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-rose-500 to-pink-500 px-8 py-6">
                <h2 class="text-3xl font-bold text-blue-300">In Loving Memory of Our Mother</h2>
            </div>
            <div class="p-8">
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Image Placeholder -->
                    <div class="md:col-span-1">
                        @if($memorial->mother_photo)
                            <img src="{{ Storage::url($memorial->mother_photo) }}"
                                 alt="Solape Elizabeth Olorunsola"
                                 class="w-full aspect-square object-cover rounded-lg border-4 border-rose-200 dark:border-rose-800 shadow-lg">
                        @else
                            <div class="aspect-square bg-gradient-to-br from-rose-100 to-pink-100 dark:from-rose-900/20 dark:to-pink-900/20 rounded-lg overflow-hidden border-4 border-rose-200 dark:border-rose-800 flex items-center justify-center">
                                <div class="text-center p-4">
                                    <svg class="w-24 h-24 mx-auto text-rose-300 dark:text-rose-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-rose-500 dark:text-rose-400 font-medium">Photo of Mom</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Add photo via admin panel</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="md:col-span-2">
                        <div class="space-y-4 text-gray-700 dark:text-gray-300">
                            <div>
                                <p class="text-2xl font-bold text-rose-600 dark:text-rose-400">
                                    Solape Elizabeth Olorunsola
                                </p>
                                <p class="text-lg text-gray-600 dark:text-gray-400 italic">
                                    (Née Babalola)
                                </p>
                                <p class="text-md text-gray-500 dark:text-gray-400 mt-1">
                                    Fondly known as <span class="font-semibold">Mama Keji</span> or <span class="font-semibold">Solape</span>
                                </p>
                                <p class="text-md text-gray-500 dark:text-gray-400 mt-2">
                                    From Igbomoji Compound, Isan-Ekiti
                                </p>
                                <p class="leading-relaxed font-semibold text-gray-900 dark:text-white">
                                    Died: January 2004
                                </p>
                            </div>

                            <div class="mt-6 space-y-4">
                                <p class="leading-relaxed">
                                    Solape Elizabeth Olorunsola, née Babalola, was born and raised in the historic Igbomoji Compound in Isan-Ekiti. From her earliest years, she demonstrated a natural gift for teaching and an insatiable hunger for knowledge. After completing her education, she dedicated herself entirely to the noble profession of teaching.
                                </p>

                                <p class="leading-relaxed">
                                    For over three decades, Mama Keji served as an educator in various schools across Ekiti State, touching the lives of thousands of students. She was more than a teacher—she was a mentor, counselor, and unwavering advocate for every child who entered her classroom. Her classroom was known as a place of warmth, discipline, and transformation. She had a unique ability to see potential in every child, even those whom others had written off.
                                </p>

                                <p class="leading-relaxed">
                                    What set Mama Keji apart was her profound understanding that education extended far beyond textbooks. She believed true education meant building character, instilling values, and nurturing the whole person. She would stay after school hours to help struggling students, often providing academic support, emotional encouragement, and sometimes material assistance from her own modest means.
                                </p>

                                <p class="leading-relaxed">
                                    Beyond the classroom, Solape was a pillar of the Isan-Ekiti community, actively involved in development initiatives focused on education and youth empowerment. She organized adult literacy programs and advocated tirelessly for better educational facilities. As a woman in education during an era when women's voices were often marginalized, she championed girls' education with particular passion, counseling many young women to pursue their dreams despite societal obstacles.
                                </p>

                                <p class="leading-relaxed">
                                    As a mother, Solape raised her four children—Morenikeji, Abayomi, Olumide, and Olalekan—with love, wisdom, and an uncompromising commitment to education and good character. She instilled in them values of integrity, service, and compassion that continue to guide them.
                                </p>

                                <p class="leading-relaxed">
                                    When Mama Keji passed away in January 2004, her funeral was attended by hundreds of former students, colleagues, and community members, all testifying to her profound impact. Her legacy continues through this scholarship program, ensuring that her vision of educational access and empowerment for the youth of Isan-Ekiti reaches new generations.
                                </p>

                                <p class="leading-relaxed font-medium text-rose-700 dark:text-rose-400">
                                    Mama Keji understood that investment in education is investment in the future. This scholarship program continues her life's work, ensuring that financial constraints never stand between deserving young people from Isan-Ekiti and their educational dreams.
                                </p>
                            </div>

                            <blockquote class="border-l-4 border-rose-500 pl-4 italic text-gray-600 dark:text-gray-400 mt-6 bg-rose-50 dark:bg-rose-900/20 p-4 rounded">
                                "Education is not just about books—it's about building character, instilling values, and empowering the next generation to become leaders who will transform the community."
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Father's Memorial -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <h2 class="text-3xl font-bold text-blue">In Loving Memory of Our Father</h2>
            </div>
            <div class="p-8">
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Image Placeholder -->
                    <div class="md:col-span-1">
                        @if($memorial->father_photo)
                            <img src="{{ Storage::url($memorial->father_photo) }}"
                                 alt="Akinola Sanmi Peter Olorunsola"
                                 class="w-full aspect-square object-cover rounded-lg border-4 border-blue-200 dark:border-blue-800 shadow-lg">
                        @else
                            <div class="aspect-square bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg overflow-hidden border-4 border-blue-200 dark:border-blue-800 flex items-center justify-center">
                                <div class="text-center p-4">
                                    <svg class="w-24 h-24 mx-auto text-blue-300 dark:text-blue-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-blue-500 dark:text-blue-400 font-medium">Photo of Dad</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Add photo via admin panel</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="md:col-span-2">
                        <div class="space-y-4 text-gray-700 dark:text-gray-300">
                            <div>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    Akinola Sanmi Peter Olorunsola
                                </p>
                                <p class="text-md text-gray-500 dark:text-gray-400 mt-1">
                                    Fondly known as <span class="font-semibold">Oga Peter</span> or <span class="font-semibold">Akin</span>
                                </p>
                                <p class="text-md text-gray-500 dark:text-gray-400 mt-2">
                                    From Ilale, Isan-Ekiti
                                </p>
                                    <p class="leading-relaxed font-semibold text-gray-900 dark:text-white">
                                    Died: April 2016
                                </p>

                            </div>

                            <div class="mt-6 space-y-4">
                                <p class="leading-relaxed">
                                    Akinola Sanmi Peter Olorunsola was born in Ilale, a historic quarter of Isan-Ekiti, into a family known for its integrity and commitment to community service. From his youth, he exhibited unwavering honesty, deep respect for tradition, and an innate sense of responsibility to his community—qualities that would define his distinguished career in public service spanning several decades.
                                </p>

                                <p class="leading-relaxed">
                                    In an era when public service was often marred by corruption, Oga Peter stood as a shining example of what a dedicated civil servant should be. His colleagues knew him as someone who could not be compromised, who treated every responsibility with seriousness, and who viewed his position not as a source of personal enrichment but as an opportunity to serve the public good. He was known for making decisions based on what was right rather than what was popular, earning deep respect from both superiors and subordinates.
                                </p>

                                <p class="leading-relaxed">
                                    While his professional work was in the civil service, Akinola's passion for education was evident throughout his life. He believed deeply that education was the most powerful tool for individual advancement and national development. He was a regular presence at school events in Isan-Ekiti, speaking to students about the importance of education and character. Beyond speeches, he quietly assisted many young people with school fees, books, and other educational expenses, viewing it as his responsibility to the community.
                                </p>

                                <p class="leading-relaxed">
                                    In Isan-Ekiti, particularly in Ilale, Akinola was respected as a community elder and leader. People sought his counsel on various matters, knowing his advice would be wise and grounded in both traditional wisdom and modern understanding. He was actively involved in community development projects, always emphasizing initiatives that would benefit the youth.
                                </p>

                                <p class="leading-relaxed">
                                    As a father, Akinola was both firm and loving, setting high standards for his children—Morenikeji, Abayomi, Olumide, and Olalekan—through his own example. He showed them what it meant to work hard, maintain integrity, and never forget one's roots or responsibilities to the community. He taught them that education was not a means to wealth or status, but a tool for service.
                                </p>

                                <p class="leading-relaxed">
                                    When Akinola passed away in April 2016, the outpouring of grief from across Ekiti State testified to the breadth of his impact. His legacy lives on through this scholarship program, ensuring that his belief in the transformative power of education and commitment to supporting young people's aspirations continue to create opportunities for the youth of Isan-Ekiti.
                                </p>

                                <p class="leading-relaxed font-medium text-blue-700 dark:text-blue-400">
                                    Akinola Olorunsola understood that true wealth lies not in what we accumulate for ourselves, but in what we invest in others—particularly in the education and development of young people. This scholarship program continues that investment, ensuring his vision of an educated, empowered Isan-Ekiti becomes reality.
                                </p>
                            </div>

                            <blockquote class="border-l-4 border-blue-600 pl-4 italic text-gray-600 dark:text-gray-400 mt-6 bg-blue-50 dark:bg-blue-900/20 p-4 rounded">
                                "The greatest service we can render to the community is to ensure that the children and youth have access to quality education. For in their success lies the future prosperity of Isan-Ekiti."
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Their Children -->
        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Their Beloved Children</h2>
            <p class="text-center text-gray-600 dark:text-gray-400 mb-6">
                Their legacy continues through their children, who honor their memory by establishing this scholarship program
            </p>
            <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border-l-4 border-purple-500">
                    <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        Dr. Morenikeji Owolabi
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 italic">
                        (Née Olorunsola)
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border-l-4 border-purple-500">
                    <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        Mr. Abayomi Olorunsola
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border-l-4 border-purple-500">
                    <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        Barrister Olumide Olorunsola
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border-l-4 border-purple-500">
                    <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        Engr. Olalekan Olasunkanmi Olorunsola
                    </p>
                </div>
            </div>
            <p class="text-center text-gray-600 dark:text-gray-400 mt-6 italic">
                Together, they ensure their parents' vision of educational empowerment lives on
            </p>
        </div>

        <!-- Their Legacy -->
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-900 rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Their Enduring Legacy</h2>
            <div class="space-y-4 text-gray-700 dark:text-gray-300">
                <p class="leading-relaxed text-center text-lg">
                    Together, Solape and Akinsola Olorunsola embodied the values that make Isan-Ekiti strong: dedication to education, commitment to service, and unwavering belief in the potential of our youth.
                </p>

                <p class="leading-relaxed">
                    From the classroom where our mother inspired students daily, to our father's office where he worked to build a better society through public service, their lives were a testament to the power of education and service. They understood that the path to community development runs through the school gate, and that investing in young people is the surest way to secure a prosperous future for Isan-Ekiti.
                </p>

                <p class="leading-relaxed">
                    Though they are no longer with us physically—our father departing in 2016 and our mother in early 2004—their vision lives on stronger than ever. This scholarship program is not just a memorial; it is the continuation of their life's work. It is our mother's classroom extended to reach students across Isan-Ekiti. It is our father's counsel and mentorship made available to a new generation. It is their shared dream of an educated, empowered community becoming reality.
                </p>

                <p class="leading-relaxed">
                    Every student who receives this scholarship carries forward their legacy. Every graduate who goes on to serve and transform their community is a living testament to their belief that education changes everything. Every success story is proof that their vision for Isan-Ekiti's youth was not in vain.
                </p>

                <p class="leading-relaxed font-medium">
                    This is more than a scholarship—it is their love for Isan-Ekiti made tangible, their commitment to education made perpetual, and their hope for future generations made real. Through the Isan-Ekiti Indigene Scholarship Program, Solape and Akinsola Olorunsola continue to teach, mentor, and inspire, ensuring that no deserving student is left behind due to financial constraints.
                </p>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-300 dark:border-gray-600 text-center">
                <p class="text-xl font-semibold text-gray-800 dark:text-gray-200 italic">
                    "In honoring their memory, we transform lives. In transforming lives, we build the Isan-Ekiti our parents dreamed of—educated, empowered, and thriving."
                </p>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                    ~ Solape Elizabeth Olorunsola (January 2004) and Akinsola Sanmi Peter Olorunsola (April 2016) ~
                </p>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="mt-12 text-center">
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Honor their memory by supporting the next generation of scholars
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('eligibility') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-150">
                    Check if You Qualify
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 font-semibold rounded-lg border-2 border-indigo-600 dark:border-indigo-400 hover:bg-indigo-50 dark:hover:bg-gray-700 transition duration-150">
                    Apply for Scholarship
                </a>
            </div>
        </div>

    </div>
</x-public-layout>
