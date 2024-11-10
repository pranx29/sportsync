<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
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
import { CircleUser, Menu, Search } from "lucide-vue-next";
</script>

<template>
    <div>
        <div class="min-h-screen bg-background">
            <header
                class="sticky top-0 flex h-16 items-center gap-4 border-b bg-background px-4 md:px-6"
            >
                <nav
                    class="hidden flex-col gap-6 text-lg font-medium md:flex md:flex-row md:items-center md:gap-5 md:text-sm lg:gap-6"
                >
                    <Link
                        :href="route('dashboard')"
                        class="flex items-center gap-2 text-lg font-semibold md:text-base"
                    >
                        <ApplicationLogo class="h-6 w-6" />
                        <span class="sr-only"> GoCo , Inc • SL </span>
                    </Link>
                    <NavLink :href="route('dashboard')">Groups</NavLink>
                    <NavLink :href="route('dashboard')">Events</NavLink>
                    <NavLink :href="route('dashboard')">Sessions</NavLink>
                </nav>
                <Sheet>
                    <SheetTrigger as-child>
                        <Button
                            variant="outline"
                            size="icon"
                            class="shrink-0 md:hidden"
                        >
                            <Menu class="h-5 w-5" />
                            <span class="sr-only">Toggle navigation menu</span>
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="left">
                        <nav class="grid gap-6 text-lg font-medium">
                            <a
                                href="#"
                                class="flex items-center gap-2 text-lg font-semibold"
                            >
                                <ApplicationLogo class="h-6 w-6" />
                                <span class="sr-only">Acme Inc</span>
                            </a>
                            <ResponsiveNavLink
                                :href="route('dashboard')"
                                active
                            >
                                Groups</ResponsiveNavLink
                            >
                            <ResponsiveNavLink :href="route('dashboard')">
                                Events
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('dashboard')">
                                Sessions
                            </ResponsiveNavLink>
                        </nav>
                    </SheetContent>
                </Sheet>
                <div
                    class="flex w-full items-center gap-4 md:ml-auto md:gap-2 lg:gap-4"
                >
                    <form class="ml-auto flex-1 sm:flex-initial">
                        <div class="relative">
                            <Search
                                class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground"
                            />
                            <Input
                                type="search"
                                placeholder="Search events and groups"
                                class="pl-8 sm:w-[300px] md:w-[200px] lg:w-[300px]"
                            />
                        </div>
                    </form>
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
                            <DropdownMenuItem>
                                <Link :href="route('profile.edit')">
                                    Settings
                                </Link>
                            </DropdownMenuItem>
                            <DropdownMenuItem>
                                <Link :href="route('profile.show')">
                                    View Profile
                                </Link>
                            </DropdownMenuItem>
                            <DropdownMenuItem>Support</DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Logout
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </header>

            <!-- Page Content -->
            <div>
                <slot />
            </div>
        </div>
    </div>
</template>
