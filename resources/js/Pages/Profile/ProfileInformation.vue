<template>
    <Card class="w-full">
        <CardHeader>
            <CardTitle>My Profile</CardTitle>
        </CardHeader>
        <CardContent class="flex flex-col gap-4">
            <!-- Profile picture -->
            <div class="flex flex-row items-center justify-center">
                <img
                    :src="$page.props.profile.profile_image"
                    alt="Profile Picture"
                    class="w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl rounded-sm object-cover"
                />
            </div>
            <!-- Profile details -->
            <div class="flex flex-col items-start justify-start">
                <h4 class="scroll-m-20 text-xl font-semibold tracking-tight">
                    {{ $page.props.auth.user.first_name }}
                    {{ $page.props.auth.user.last_name }}
                </h4>
                <h5 class="scroll-m-20 text-md font-medium tracking-tight">
                    {{ $page.props.profile.role.title }}
                </h5>
                <div class="flex items-center space-x-2 mt-2">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-4 text-foreground"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"
                        />
                    </svg>

                    <p class="scroll-m-20 text-sm font-normal tracking-tight">
                        {{ $page.props.profile.department.name }}
                    </p>
                </div>
                <div class="flex items-center space-x-2 mt-2">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-4 text-foreground"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"
                        />
                    </svg>

                    <p class="scroll-m-20 text-sm font-normal tracking-tight">
                        {{ $page.props.auth.user.email }}
                    </p>
                </div>
            </div>
            <!-- Count of Interest, number of groups, joined event, points -->
            <div class="flex justify-evenly w-full">
                <div class="flex flex-col items-center justify-center">
                    <span
                        class="scroll-m-20 text-xl font-semibold tracking-tight text-primary"
                    >
                        {{ $page.props.interests.length }}
                    </span>

                    <p class="scroll-m-20 text-sm font-medium tracking-tight">
                        Interests
                    </p>
                </div>
                <Separator class="border" orientation="vertical" />
                <div class="flex flex-col items-center justify-center">
                    <span
                        class="scroll-m-20 text-xl font-semibold tracking-tight text-primary"
                    >
                        3
                    </span>

                    <p class="scroll-m-20 text-sm font-medium tracking-tight">
                        Groups
                    </p>
                </div>
                <Separator class="border" orientation="vertical" />
                <div class="flex flex-col items-center justify-center">
                    <span
                        class="scroll-m-20 text-xl font-semibold tracking-tight text-primary"
                    >
                        2
                    </span>

                    <p class="scroll-m-20 text-sm font-medium tracking-tight">
                        Events
                    </p>
                </div>
                <Separator class="border" orientation="vertical" />
                <div class="flex flex-col items-center justify-center">
                    <span
                        class="scroll-m-20 text-xl font-semibold tracking-tight text-primary"
                    >
                        125
                    </span>

                    <p class="scroll-m-20 text-sm font-medium tracking-tight">
                        Points
                    </p>
                </div>
            </div>
        </CardContent>
        <CardFooter> </CardFooter>
    </Card>
</template>
