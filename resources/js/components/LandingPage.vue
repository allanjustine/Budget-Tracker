<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Link } from '@inertiajs/vue3';
import { HandCoinsIcon } from 'lucide-vue-next';
</script>

<template>
    <!-- Hero Section -->
    <section class="container mx-auto px-6 py-20">
        <div class="flex flex-col items-center gap-12 md:flex-row">
            <div class="md:w-1/2">
                <Badge variant="outline" class="mb-4 border-blue-200 bg-blue-100 p-2 rounded text-blue-600">New Feature</Badge>
                <h1 class="mb-6 text-5xl leading-tight font-bold text-gray-600 dark:text-gray-200">Take Control of Your <span class="text-blue-600">Finances</span></h1>
                <p class="mb-8 text-xl text-gray-500 dark:text-gray-400">
                    E-Track helps you manage your expenses effortlessly with beautiful insights and powerful analytics.
                </p>
                <div class="flex space-x-4">
                    <Button class="bg-blue-600 px-8 py-6 text-lg text-white hover:bg-blue-700">Get Started</Button>
                    <Button variant="outline" class="border-gray-300 px-8 py-6 text-lg text-gray-600 dark:text-gray-300 hover:bg-gray-50">
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
                            class="mr-2"
                        >
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                        Watch Demo
                    </Button>
                </div>
            </div>
            <div class="md:w-1/2">
                <Card class="border-0 bg-blue-600/80 dark:bg-blue-900/80 shadow-xl">
                    <CardHeader>
                        <CardTitle class="text-xl dark:text-gray-300 text-gray-200">Expense Dashboard</CardTitle>
                        <CardDescription class="text-gray-300">Your monthly spending overview</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="flex h-64 items-center justify-center rounded-lg bg-gradient-to-r from-blue-50 to-purple-50">
                            <div class="text-center">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="48"
                                    height="48"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="mx-auto mb-4 text-blue-400"
                                >
                                    <line x1="12" y1="20" x2="12" y2="10"></line>
                                    <line x1="18" y1="20" x2="18" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="16"></line>
                                </svg>
                                <p class="text-gray-500">Interactive chart preview</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20">
        <div class="container mx-auto px-6">
            <div class="mb-16 text-center">
                <Badge variant="outline" class="mb-4 dark:border-blue-900 dark:bg-blue-900 dark:text-white border-blue-200 bg-blue-50 text-blue-600 p-2 rounded">Features</Badge>
                <h2 class="mb-4 text-4xl font-bold dark:text-gray-300 text-gray-900">Powerful Expense Tracking</h2>
                <p class="mx-auto max-w-2xl text-xl dark:text-gray-200 text-gray-600">Everything you need to manage your finances in one beautiful interface</p>
            </div>

            <div class="grid gap-8 md:grid-cols-3">
                <Card class="border-0 dark:bg-blue-900/80 bg-blue-600/80 transition duration-300 hover:shadow-lg">
                    <CardHeader>
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
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
                                class="text-blue-600"
                            >
                                <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                <path d="M3 3v5h5"></path>
                                <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"></path>
                                <path d="M16 16h5v5"></path>
                            </svg>
                        </div>
                        <CardTitle class="dark:text-gray-200 text-gray-100">Real-time Sync</CardTitle>
                        <CardDescription class="text-gray-200 dark:text-gray-300">All your devices updated instantly</CardDescription>
                    </CardHeader>
                </Card>

                <Card class="border-0 dark:bg-blue-900/80 bg-blue-600/80 transition duration-300 hover:shadow-lg">
                    <CardHeader>
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                            <HandCoinsIcon class="text-blue-500" />
                        </div>
                        <CardTitle class="dark:text-gray-200 text-gray-100">Smart Budgeting</CardTitle>
                        <CardDescription class="text-gray-200 dark:text-gray-300">Automated budget recommendations</CardDescription>
                    </CardHeader>
                </Card>

                <Card class="border-0 dark:bg-blue-900/80 bg-blue-600/80 transition duration-300 hover:shadow-lg">
                    <CardHeader>
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
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
                                class="text-blue-600"
                            >
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                            </svg>
                        </div>
                        <CardTitle class="dark:text-gray-200 text-gray-100">Detailed Reports</CardTitle>
                        <CardDescription class="text-gray-200 dark:text-gray-300">Beautiful visualizations of your spending</CardDescription>
                    </CardHeader>
                </Card>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20">
        <div class="container mx-auto px-6">
            <div class="mb-16 text-center">
                <Badge variant="outline" class="mb-4 dark:border-blue-800 border-blue-200 dark:bg-blue-900 p-2 dark:text-white rounded bg-blue-50 text-blue-600">Testimonials</Badge>
                <h2 class="mb-4 text-4xl font-bold text-gray-900 dark:text-gray-200">Trusted by Thousands</h2>
                <p class="mx-auto max-w-2xl text-xl text-gray-600 dark:text-gray-300">Join our community of happy users who transformed their finances</p>
            </div>

            <div class="grid gap-8 md:grid-cols-3">
                <Card class="border-0 bg-blue-600/80 dark:bg-blue-900/80">
                    <CardContent class="pt-6">
                        <div class="mb-4 flex items-center">
                            <Avatar class="mr-4">
                                <AvatarImage src="https://randomuser.me/api/portraits/women/44.jpg" />
                                <AvatarFallback>JD</AvatarFallback>
                            </Avatar>
                            <div>
                                <p class="font-medium text-white">Jane Doe</p>
                                <p class="text-sm text-gray-200">Marketing Director</p>
                            </div>
                        </div>
                        <p class="text-gray-300">
                            "E-Track helped me save 30% more each month without feeling restricted. The insights are incredibly valuable!"
                        </p>
                    </CardContent>
                </Card>

                <Card class="border-0 bg-blue-600/80 dark:bg-blue-900/80">
                    <CardContent class="pt-6">
                        <div class="mb-4 flex items-center">
                            <Avatar class="mr-4">
                                <AvatarImage src="https://randomuser.me/api/portraits/men/32.jpg" />
                                <AvatarFallback>JS</AvatarFallback>
                            </Avatar>
                            <div>
                                <p class="font-medium text-white">John Smith</p>
                                <p class="text-sm text-gray-200">Freelancer</p>
                            </div>
                        </div>
                        <p class="text-gray-300">
                            "As a freelancer, tracking expenses was chaotic. E-Track simplified everything with its beautiful interface."
                        </p>
                    </CardContent>
                </Card>

                <Card class="border-0 bg-blue-600/80 dark:bg-blue-900/80">
                    <CardContent class="pt-6">
                        <div class="mb-4 flex items-center">
                            <Avatar class="mr-4">
                                <AvatarImage src="https://randomuser.me/api/portraits/women/68.jpg" />
                                <AvatarFallback>SR</AvatarFallback>
                            </Avatar>
                            <div>
                                <p class="font-medium text-white">Sarah Roberts</p>
                                <p class="text-sm text-gray-200">Small Business Owner</p>
                            </div>
                        </div>
                        <p class="text-gray-300">
                            "The reporting features saved me hours each month. Now I can focus on growing my business instead of spreadsheets."
                        </p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-blue-600 dark:bg-blue-800 py-20 text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="mb-6 text-4xl font-bold">Ready to Transform Your Finances?</h2>
            <p class="mx-auto mb-8 max-w-2xl text-xl opacity-90">Join thousands of users who took control of their spending with E-Track</p>
            <div class="flex justify-center space-x-4">
                <Link href="/login"><Button class="bg-white px-8 py-6 text-lg text-blue-600 hover:bg-gray-100">Get Started</Button> </Link>
                <Link href="#">
                    <Button variant="outline" class="border-white px-8 py-6 text-lg text-white hover:text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">Learn More</Button>
                </Link>
            </div>
        </div>
    </section>
</template>
