<script setup lang="ts">
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import {LoginWidget} from 'vue-tg';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {useDark} from '@vueuse/core'
import {computed, ref} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import CancelIcon from "@/Components/CancelIcon.vue";
import DangerButton from "@/Components/DangerButton.vue";
import PlusIcon from "@/Components/PlusIcon.vue";

const isDark = useDark();
const page = usePage();

const logoColor = computed(() => isDark.value ? 'white' : 'black');
const isLogged = computed(() => page.props.auth.user !== null);

const isCreatingRoom = ref<boolean>(false);
const roomForm = useForm<{
    code: string;
    members: string[];
}>({
    code: '',
    members: [],
});

const removeMember = (index: number) => roomForm.members.splice(index, 1);
const newMember = ref<string>('');
const addNewMember = () => {
    if (newMember.value.trim() === '') {
        return;
    }

    roomForm.members.push(newMember.value);
    newMember.value = '';
};
const storeRoom = () => {
    roomForm.clearErrors();
    roomForm.post(route('room.store'));
    isCreatingRoom.value = false;
};

defineProps<{
    appName: string;
    appVersion: string;
}>();

function getFirstArrayError(errors: Record<string, string>, key: string): string | null {
    const errorKey = Object.keys(errors).find(i => i.startsWith(key + '.'));
    return errorKey ? errors[errorKey] : null;
}

</script>

<template>
    <Head title="Home Page"/>
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="flex flex-col items-center gap-2 py-8">
                    <ApplicationLogo :color="logoColor"/>
                    <h4 class="text-black dark:text-white uppercase font-bold">Who Spoke?</h4>
                    <h1 class="text-3xl text-black dark:text-white uppercase font-bold">Chi ha parlato?</h1>
                </header>

                <main>

                    <div class="flex flex-col items-center gap-2">

                        <LoginWidget
                            v-if="!isLogged"
                            bot-username="WhoSpokeBot"
                            :redirect-url="route('access')"
                            corner-radius="8"
                            :user-photo="false"
                        />

                        <p class="text-xl" v-if="isLogged">
                            Benvenuto, {{ page.props.auth.user?.first_name }}
                        </p>
                        <PrimaryButton v-if="isLogged" @click="isCreatingRoom=true">
                            Crea una sessione
                        </PrimaryButton>

                        <Modal :show="isCreatingRoom" @close="isCreatingRoom=false">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Crea una sessione
                                </h2>

                                <p class="mt-1 text-gray-600 dark:text-gray-400">
                                    Inserisci il codice della sessione che vuoi creare
                                </p>
                                <input type="text"
                                       v-model="roomForm.code"
                                       class="mt-1 w-full border border-gray-600 rounded-md p-2 dark:bg-gray-900 dark:text-white"
                                       placeholder="Codice sessione"/>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-500 italic">
                                    Verrà usato per generare un link diretto alla sessione
                                </p>
                                <p class="text-sm text-red-500" v-if="roomForm.errors.code">
                                    {{ roomForm.errors.code }}
                                </p>

                                <p class="my-2 text-gray-600 dark:text-gray-400">
                                    Membri della sessione ({{ roomForm.members.length }}/20)
                                </p>

                                <div class="flex gap-2 mb-2" v-for="(name,index) in roomForm.members" :key="index">
                                    <div class="flex-1">
                                        <input type="text"
                                               v-model="roomForm.members[index]"
                                               class="w-full border border-gray-600 rounded-md p-2 dark:bg-gray-900 dark:text-white"
                                               placeholder="Nome membro"/>
                                    </div>
                                    <div>
                                        <DangerButton @click="()=>removeMember(index)">
                                            <CancelIcon :size="24" color="white"/>
                                        </DangerButton>
                                    </div>
                                </div>

                                <div class="flex gap-2 mb-2">
                                    <div class="flex-1">
                                        <input type="text"
                                               v-model="newMember"
                                               @keyup.enter="addNewMember"
                                               class="w-full border border-gray-600 rounded-md p-2 dark:bg-gray-900 dark:text-white"
                                               placeholder="Nome membro"/>
                                    </div>
                                    <div>
                                        <PrimaryButton @click="addNewMember">
                                            <PlusIcon :size="24" :color="isDark ? 'black' : 'white'"/>
                                        </PrimaryButton>
                                    </div>
                                </div>

                                <p class="text-sm text-red-500" v-if="roomForm.errors.members">
                                    {{ roomForm.errors.members }}
                                </p>

                                <p class="text-sm text-red-500" v-if="getFirstArrayError(roomForm.errors, 'members')">
                                    {{ getFirstArrayError(roomForm.errors, 'members') }}
                                </p>

                                <div class="mt-4 flex justify-end gap-4">
                                    <button type="button" class="text-red-500 hover:underline"
                                            @click="isCreatingRoom=false">
                                        Annulla
                                    </button>
                                    <PrimaryButton @click="storeRoom">
                                        Crea sessione
                                    </PrimaryButton>
                                </div>
                            </div>
                        </Modal>

                        <p class="text-red-500" v-if="!isLogged">
                            Per creare una sessione, devi prima eseguire il login.
                        </p>

                        <p class="text-xl mt-2">Per unirti in una sessione, apri un link diretto alla sessione.</p>

                        <Link :href="route('logout')" method="post"
                              v-if="isLogged"
                              as="button"
                              type="button"
                              class="text-red-500 hover:underline">
                            Logout
                        </Link>

                    </div>
                </main>

                <footer class="py-10 text-center text-sm text-black dark:text-white/70">
                    <a href="https://github.com/Lukasss93/whospoke"
                       target="_blank"
                       class="text-blue-500 hover:underline">
                        {{ appName }}
                    </a> v{{ appVersion }} -
                    Developed by
                    <a href="https://www.lucapatera.it"
                       target="_blank"
                       class="text-blue-500 hover:underline">
                        Luca Patera
                    </a>
                </footer>
            </div>
        </div>
    </div>
</template>
