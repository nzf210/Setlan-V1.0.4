<script setup lang="ts">
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import EyeHide from "@/Components/Icons/EyeHide.vue";
import EyeShow from "@/Components/Icons/EyeShow.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm } from "@inertiajs/vue3";
import HeadTitle from "@/Components/HeadTitle.vue";
import { watch, ref } from "vue";

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    username: "",
    password: "",
    remember: false,
});

const setFirstLoginFlag = () => {
    localStorage.setItem("isFirstLogin", "true");
};

const submit = () => {
    form.post(route("login"), {
        onFinish: () => {
            form.reset("password");
        },
    });
    setFirstLoginFlag();
};

const typePass = ref("password");
const typeView = ref(false);

watch(
    () => form.password,
    (newValue) => {
        if (newValue !== "") {
            console.log("watch", newValue);
            typeView.value = true;
        } else {
            typeView.value = false;
        }
    }
);

const togglePass = () => {
    typePass.value = typePass.value === "password" ? "text" : "password";
};
</script>

<template>
    <HeadTitle :title-head="'Login'" />
    <GuestLayout>
        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="user" value="Username" />
                <TextInput id="user" type="text" class="mt-1 block w-full" v-model="form.username" required autofocus />
                <InputError class="mt-2" :message="form.errors.username" />
            </div>

            <div class="mt-4 relative">
                <InputLabel for="password" value="Password" />
                <TextInput id="password" :type="typePass ?? 'password'" class="mt-1 block w-full"
                    v-model="form.password" required />
                <InputError class="mt-2" :message="form.errors.password" />
                <div v-if="typeView" class="flex items-center absolute inset-y-0 right-0 pr-3 pt-5">
                    <EyeHide v-if="typePass === 'password'" class="cursor-pointer text-slate-500" @click="togglePass" />
                    <EyeShow v-else class="cursor-pointer" @click="togglePass" />
                </div>
            </div>

            <div class="block mt-4">
                <div class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"> Remember me </span>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Forgot your password?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
