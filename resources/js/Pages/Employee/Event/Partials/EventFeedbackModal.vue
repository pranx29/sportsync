<script setup>
import { Star } from "lucide-vue-next";
import { Button } from "@/Components/ui/button";
import { Textarea } from "@/Components/ui/textarea";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";
import { toast } from "@/Components/ui/toast";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";


const props = defineProps({
    eventId: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(["feedbackSubmitted"]);

const rating = ref(0);
const comment = ref("");
const isFeedFormOpen = ref(false);

const setRating = (value) => {
    rating.value = value;
};

const onCancel = () => {
    resetForm();
    isFeedFormOpen.value = false;
};

const resetForm = () => {
    rating.value = 0;
    comment.value = "";
};

const handleSubmit = () => {
    if (rating.value === 0) return;

    // router.post(
    //     route("group.events.feedback", { event: props.eventId }),
    //     {
    //         rating: rating.value,
    //         comment: comment.value,
    //     },
    //     {
    //         preserveScroll: true,
    //         preserveState: true,
    //         onSuccess: () => {
    //             toast({
    //                 title: "Feedback submitted",
    //                 description:
    //                     "Your feedback has been submitted successfully.",
    //                 variant: "success",
    //             });
    //             emit("feedbackSubmitted");
    //         },
    //         onError: () => {
    //             toast({
    //                 title: "Failed to submit feedback",
    //                 description:
    //                     "An error occurred while submitting your feedback.",
    //                 variant: "destructive",
    //             });
    //         },
    //     }
    // );


    const feedback = {
        rating: rating.value,
        comment: comment.value,
    };

    console.log("Feedback submitted:", feedback);
    resetForm();
    isFeedFormOpen.value = false;
};
</script>

<template>
    <Dialog v-model:open="isFeedFormOpen">
        <DialogTrigger asChild>
            <Button variant="secondary" size="sm" class="w-full">
                <Star class="w-4 h-4 mr-2" />
                Add Feedback
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Add Your Feedback</DialogTitle>
                <DialogDescription>
                    Share your thoughts about the event. Your feedback helps
                    us improve!
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                        >Rating</label
                    >
                    <div class="flex items-center">
                        <button
                            v-for="value in [1, 2, 3, 4, 5]"
                            :key="value"
                            type="button"
                            @click="setRating(value)"
                            class="p-1"
                        >
                            <Star
                                :class="{
                                    'w-6 h-6 text-yellow-400 fill-yellow-400':
                                        value <= rating,
                                    'w-6 h-6 text-gray-300': value > rating,
                                }"
                            />
                        </button>
                    </div>
                </div>
                <div>
                    <label
                        for="comment"
                        class="block text-sm font-medium text-gray-700 mb-1"
                    >
                        Comment (optional)
                    </label>
                    <Textarea
                        id="comment"
                        v-model="comment"
                        rows="4"
                        placeholder="Share your thoughts about the event..."
                    />
                </div>
                <div class="flex justify-end space-x-2">
                    <Button type="button" variant="outline" @click="onCancel">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="rating === 0">
                        Submit Feedback
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
