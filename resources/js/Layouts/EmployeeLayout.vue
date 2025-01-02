<script setup>
import Logo from "@/Components/Logo.vue";
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
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";
import { Separator } from "@/Components/ui/separator";
import { Toaster } from "@/Components/ui/toast";
import ThemeModeToggle from "@/Components/ThemeModeToggle.vue";
</script>

<template>
    <div>
        <div class="min-h-screen bg-background grid grid-rows-[auto_1fr]">
            <header
                class="sticky top-0 flex h-16 items-center gap-4 border-b bg-background px-4 md:px-6"
            >
                <nav
                    class="hidden flex-col gap-6 text-lg font-medium md:flex md:flex-row md:items-center md:gap-5 md:text-sm lg:gap-6 w-full justify-between"
                >
                    <Link
                        :href="route('dashboard')"
                        class="flex items-center gap-4 font-semibold"
                    >
                        <Logo class="h-8 w-8 text-primary" />
                        <span class="">{{ $page.props.companyName }}</span>
                    </Link>

                    <NavLink
                        :href="route('employee.groups')"
                        :active="route().current('employee.groups')"
                        >Groups</NavLink
                    >
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
                                <Logo class="h-6 w-6" />
                                <span class="sr-only">{{
                                    $page.props.companyName
                                }}</span>
                            </a>
                            <ResponsiveNavLink
                                :href="route('employee.groups')"
                                active
                            >
                                Groups</ResponsiveNavLink
                            >
                        </nav>
                    </SheetContent>
                </Sheet>
                <div
                    class="flex w-full items-center gap-4 md:ml-auto md:gap-2 lg:gap-4"
                >
                    <form class="ml-auto flex-1 sm:flex-initial">
                        <!-- <div class="relative">
                    <Search
                    class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground"
                    />
                    <Input
                    type="search"
                    placeholder="Search events and groups"
                    class="pl-8 sm:w-[300px] md:w-[200px] lg:w-[300px]"
                    />
                </div> -->
                    </form>
                    <div class="flex items-center gap-2">
                        <ThemeModeToggle />
                        <Separator orientation="vertical" class="h-8" />

                        <div>
                            {{ $page.props.auth.user.first_name }}
                            {{ $page.props.auth.user.last_name }}
                        </div>
                    </div>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button
                                variant="secondary"
                                size="icon"
                                class="rounded-full"
                            >
                                <Avatar>
                                    <AvatarImage
                                        :src="$page.props.auth.profile_image"
                                        alt="@radix-vue"
                                    />
                                    <AvatarFallback
                                        >{{
                                            $page.props.auth.user.first_name.charAt(
                                                0
                                            )
                                        }}{{
                                            $page.props.auth.user.last_name.charAt(
                                                0
                                            )
                                        }}</AvatarFallback
                                    >
                                </Avatar>

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
            <div class="p-4">
                <slot />
            </div>
        </div>
    </div>
    <Toaster />
</template>
