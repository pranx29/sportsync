<script setup lang="ts">
import { Button } from "@/Components/ui/button";

import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Checkbox } from "@/Components/ui/checkbox";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Input } from "@/Components/ui/input";
import { Sheet, SheetContent, SheetTrigger } from "@/Components/ui/sheet";
import { CircleUser, Menu, Package2, Search } from "lucide-vue-next";
import Logo from "@/Components/Logo.vue";
</script>

<template>
    <div class="min-h-screen w-full">
        <div
            class="grid grid-cols-[220px_1fr] lg:grid-cols-[280px_1fr] h-14 lg:h-[60px] border-b bg-muted/40"
        >
            <div class="border-r flex items-center px-4 lg:px-6">
                <Link
                    :href="route('admin.dashboard')"
                    class="flex items-center gap-2 font-semibold"
                >
                    <Logo class="h-6 w-6 text-primary" />
                    <span class="text-nowrap">{{
                        $page.props.companyName
                    }}</span>
                </Link>
            </div>
            <header class="flex items-center w-full gap-4 px-4 lg:px-6">
                <div class="w-full flex-1">
                    <form>
                        <div class="relative">
                            <Search
                                class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground"
                            />
                            <Input
                                type="search"
                                placeholder="Search products..."
                                class="w-full appearance-none bg-background pl-8 shadow-none md:w-2/3 lg:w-1/3"
                            />
                        </div>
                    </form>
                </div>
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button
                            variant="secondary"
                            size="icon"
                            class="rounded-full"
                        >
                            <CircleUser class="h-5 w-5" />
                            <span class="sr-only">Toggle user menu</span>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>My Account</DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem>Settings</DropdownMenuItem>
                        <DropdownMenuItem>Support</DropdownMenuItem>
                        <DropdownMenuSeparator />
                        <Link :href="route('logout')" method="post">
                            <DropdownMenuItem> Logout </DropdownMenuItem>
                        </Link>
                    </DropdownMenuContent>
                </DropdownMenu>
            </header>
        </div>

        <div class="flex flex-col">
            <!-- Mobile Navigation -->

            <main
                class="flex min-h-[calc(100vh_-_theme(spacing.16))] flex-1 flex-col gap-4 bg-muted/40 p-4 md:gap-4 md:p-6"
            >
                <div class="mx-auto grid w-full max-w-6xl">
                    <h1 class="text-3xl font-semibold">Settings</h1>
                </div>
                <div
                    class="mx-auto grid w-full max-w-6xl items-start gap-6 md:grid-cols-[180px_1fr] lg:grid-cols-[250px_1fr]"
                >
                    <nav class="grid gap-4 text-sm text-muted-foreground">
                        <a href="#"> Account </a>
                        <Link
                            :href="route('admin.settings.department')"
                            :class="{
                                'font-semibold text-primary': route().current(
                                    'admin.settings.department'
                                ),
                            }"
                        >
                            Department
                        </Link>
                        <Link
                            :href="route('admin.settings.role')"
                            :class="{
                                'font-semibold text-primary': route().current(
                                    'admin.settings.role'
                                ),
                            }"
                        >
                            Role
                        </Link>
                    </nav>
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
