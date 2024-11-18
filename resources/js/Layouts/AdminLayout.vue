<script setup>
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
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
import {
    Bell,
    CircleUser,
    Home,
    LineChart,
    Ticket,
    Package2,
    Search,
    User,
    Sparkles,
    Users,
} from "lucide-vue-next";
import Logo from "@/Components/Logo.vue";
</script>

<template>
    <div
        class="grid min-h-screen w-full md:grid-cols-[220px_1fr] lg:grid-cols-[280px_1fr]"
    >
        <div class="hidden border-r bg-muted/40 md:block">
            <div class="flex h-full max-h-screen flex-col gap-2">
                <div
                    class="flex h-14 items-center border-b px-4 lg:h-[60px] lg:px-6"
                >
                    <Link
                        :href="route('admin.dashboard')"
                        class="flex items-center gap-2 font-semibold"
                    >
                        <Logo class="h-6 w-6 text-primary" />
                        <span class="">{{ $page.props.companyName }}</span>
                    </Link>
                </div>
                <div class="flex-1">
                    <nav
                        class="grid items-start px-2 text-sm font-medium lg:px-4"
                    >
                        <Link
                            :href="route('admin.dashboard')"
                            :class="{
                                'flex items-center gap-3 rounded-lg px-3 py-2 transition-all': true,
                                'bg-muted text-primary hover:text-primary':
                                    route().current('admin.dashboard'),
                                'text-muted-foreground hover:text-primary':
                                    !route().current('admin.dashboard'),
                            }"
                        >
                            <Home class="h-4 w-4" />
                            Dashboard
                        </Link>
                        <a
                            href="#"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                        >
                            <Ticket class="h-4 w-4" />
                            Events
                        </a>
                        <Link
                            :href="route('admin.groups.index')"
                            :class="{
                                'flex items-center gap-3 rounded-lg px-3 py-2 transition-all': true,
                                'bg-muted text-primary hover:text-primary':
                                    route().current('admin.groups.index'),
                                'text-muted-foreground hover:text-primary':
                                    !route().current('admin.groups.index'),
                            }"
                        >
                            <User class="h-4 w-4" />
                            Groups
                        </Link>
                        <Link
                            :href="route('admin.employees.index')"
                            :class="{
                                'flex items-center gap-3 rounded-lg px-3 py-2 transition-all': true,
                                'bg-muted text-primary hover:text-primary':
                                    route().current('admin.employees.index'),
                                'text-muted-foreground hover:text-primary':
                                    !route().current('admin.employees.index'),
                            }"
                        >
                            <User class="h-4 w-4" />
                            Employees
                        </Link>
                        <a
                            href="#"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                        >
                            <LineChart class="h-4 w-4" />
                            Reports
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="flex flex-col">
            <!-- Mobile Navigation -->
            <header
                class="flex h-14 items-center gap-4 border-b bg-muted/40 px-4 lg:h-[60px] lg:px-6"
            >
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
                    <SheetContent side="left" class="flex flex-col">
                        <nav class="grid gap-2 text-lg font-medium">
                            <a
                                href="#"
                                class="flex items-center gap-2 text-lg font-semibold"
                            >
                                <Package2 class="h-6 w-6" />
                                <span class="sr-only">Acme Inc</span>
                            </a>
                            <a
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                            >
                                <Home class="h-5 w-5" />
                                Dashboard
                            </a>
                            <a
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl bg-muted px-3 py-2 text-foreground hover:text-foreground"
                            >
                                <ShoppingCart class="h-5 w-5" />
                                Orders
                                <Badge
                                    class="ml-auto flex h-6 w-6 shrink-0 items-center justify-center rounded-full"
                                >
                                    6
                                </Badge>
                            </a>
                            <a
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                            >
                                <Package class="h-5 w-5" />
                                Products
                            </a>
                            <a
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                            >
                                <Users class="h-5 w-5" />
                                Customers
                            </a>
                            <a
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                            >
                                <LineChart class="h-5 w-5" />
                                Analytics
                            </a>
                        </nav>
                        <div class="mt-auto">
                            <Card>
                                <CardHeader>
                                    <CardTitle>Upgrade to Pro</CardTitle>
                                    <CardDescription>
                                        Unlock all features and get unlimited
                                        access to our support team.
                                    </CardDescription>
                                </CardHeader>
                                <CardContent>
                                    <Button size="sm" class="w-full">
                                        Upgrade
                                    </Button>
                                </CardContent>
                            </Card>
                        </div>
                    </SheetContent>
                </Sheet>
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
                        <Link :href="route('admin.settings.account')">
                            <DropdownMenuItem>Settings</DropdownMenuItem>
                        </Link>
                        <DropdownMenuItem>Support</DropdownMenuItem>
                        <DropdownMenuSeparator />
                        <Link :href="route('logout')" method="post">
                            <DropdownMenuItem> Logout </DropdownMenuItem>
                        </Link>
                    </DropdownMenuContent>
                </DropdownMenu>
            </header>

            <div class="flex flex-1 flex-col gap-4 p-6">
                <div class="flex items-center">
                    <h1 class="text-lg font-semibold md:text-2xl">
                        <slot name="title" />
                    </h1>
                </div>
                <div class="flex flex-1 items-center justify-center rounded-lg">
                    <!-- Page Content -->
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
