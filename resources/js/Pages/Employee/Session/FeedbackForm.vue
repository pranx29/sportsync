<script setup>
import { ref } from 'vue';
import { toast } from '@/Components/ui/toast';
import { router } from '@inertiajs/vue3';
import { Button } from "@/Components/ui/button";
import Textarea from '@/Components/ui/textarea/Textarea.vue';

const props = defineProps({
    session: Object,
    auth: Object,
});

const form = ref({
    rating: '',
    comments: '',
    errors: {}
});

const submitFeedback = async () => {
    form.value.errors = {};

    // Validate rating
    if (!form.value.rating) {
        form.value.errors.rating = 'Rating is required.';
    }

    if (Object.keys(form.value.errors).length > 0) {
        return;
    }

    try {
        const response = await router.post(route('sessions.feedback', { session: props.session.id }), {
            rating: form.value.rating,
            comments: form.value.comments,
        });

        toast({ title: 'Feedback Submitted', description: 'Your feedback has been submitted successfully.', variant: 'success' });
        // Optionally, redirect or close the form after successful submission
        form.value.rating = '';
        form.value.comments = '';
    } catch (error) {
        toast({ title: 'Error', description: error.response?.data.message || 'Failed to submit feedback.', variant: 'destructive' });
    }
};
</script>

<template>
    <div class="feedback-form">
        <h2 class="text-lg font-semibold mb-4">Provide Feedback for Session: {{ session.session_name }}</h2>
        <form @submit.prevent="submitFeedback">
            <div class="mb-4">
                <label for="rating" class="block text-sm font-medium mb-2">Rating</label>
                <select v-model="form.rating" id="rating" class="w-full border-gray-300 rounded-md">
                    <option disabled value="">Select a rating</option>
                    <option value="1">1 - Poor</option>
                    <option value="2">2 - Fair</option>
                    <option value="3">3 - Good</option>
                    <option value="4">4 - Very Good</option>
                    <option value="5">5 - Excellent</option>
                </select>
                <p v-if="form.errors.rating" class="text-red-500 text-xs mt-1">{{ form.errors.rating }}</p>
            </div>

            <div class="mb-4">
                <label for="comments" class="block text-sm font-medium mb-2">Comments</label>
                <Textarea v-model="form.comments" id="comments"></Textarea>
                <p v-if="form.errors.comments" class="text-red-500 text-xs mt-1">{{ form.errors.comments }}</p>
            </div>

            <div class="flex justify-end">
                <Button type="submit">
                    Submit
                </Button>
            </div>
        </form>
    </div>
</template>

<style scoped>
.feedback-form {
    max-width: 600px;
    margin: auto;
}
</style>
