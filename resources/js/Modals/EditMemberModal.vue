<script setup lang="ts">
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import {ref} from "vue";
import {Member} from "@/types";
import {trans} from "laravel-translator";
import AutoComplete from 'primevue/autocomplete';
import axios from "axios";
import Avatar from "primevue/avatar";


const emit = defineEmits(['close', 'save']);

const member = defineModel<Member | null>('member', {required: true});
const show = defineModel<boolean>('show', {default: false});
const userSuggestions = ref<any[]>([]);
const professionSuggestions = ref<any[]>([]);
const loading = ref(false);


function hiding(value: boolean) {
    if (!value) {
        emit('close');
    }
}

async function userSearch(event) {
    try {
        const response = await axios.get(route('users.search'), {
            params: {
                search: event.query,
            }
        });
        userSuggestions.value = response.data;
    } catch (e) {
        console.error(e);
        userSuggestions.value = [];
    }
}

async function professionSearch(event) {
    try {
        const response = await axios.get(route('professions.search'), {
            params: {
                search: event.query,
            }
        });
        professionSuggestions.value = response.data;
    } catch (e) {
        console.error(e);
        professionSuggestions.value = [];
    }
}

function userUpdateAutoCompleteValue(value) {
    if (member.value === null) {
        return;
    }

    member.value.user = value;
}

function professionUpdateAutoCompleteValue(value) {
    if (member.value === null) {
        return;
    }

    member.value.profession = value;
}

async function editMember() {
    try {
        loading.value = true;
        await axios.post(route('member.user.edit', {member: member.value?.id}), {
            user_id: member.value?.user?.id ?? null,
            profession_id: member.value?.profession?.id ?? null,
        });
        emit('save');
    } catch (e) {
        console.error(e);
    } finally {
        loading.value = false;
    }
}

</script>

<template>
    <Dialog v-model:visible="show" modal dismissableMask :style="{ width: '25rem' }"
            @update:visible="hiding">

        <template #header>
            <span class="text-lg font-medium">
                {{ trans('app.member.edit')}} ({{ member?.name}})
            </span>
        </template>

        <!-- Member User -->

        <div>
            {{ trans('app.member_user.title') }}
        </div>
        <AutoComplete class="block w-full pt-1"
                      pt:input:class="w-full"
                      :model-value="member?.user"
                      @update:model-value="userUpdateAutoCompleteValue"
                      optionLabel="name"
                      forceSelection
                      dropdown
                      :suggestions="userSuggestions"
                      @complete="userSearch">
            <template #option="{option}">
                <div class="flex items-center gap-2">
                    <div class="inline-flex">
                        <Avatar icon="pi pi-user"
                                v-if="!option.avatar"
                                class="!bg-gray-400 dark:!bg-gray-900"
                                shape="circle"/>

                        <Avatar v-if="option.avatar"
                                :image="option.avatar"
                                shape="circle"/>
                    </div>
                    <p>{{ option.name }}</p>
                </div>
            </template>
            <template #empty>
                {{ trans('app.member_user.empty') }}
            </template>
        </AutoComplete>
        <a href="#" @click.prevent="member.user=null"
           v-if="member?.user"
           class="text-sm text-red-500 hover:underline">
            {{ trans('app.member_user.detach') }}
        </a>

        <!-- Member Profession -->
        <div class="mt-4">
            {{ trans('app.member_profession.title') }}
        </div>
        <AutoComplete class="block w-full pt-1"
                      pt:input:class="w-full"
                      :model-value="member?.profession"
                      @update:model-value="professionUpdateAutoCompleteValue"
                      optionLabel="name"
                      forceSelection
                      dropdown
                      :suggestions="professionSuggestions"
                      @complete="professionSearch">
            <template #option="{option}">
                <div class="flex items-center gap-2">
                    <div class="inline-flex">
                        <Avatar :style="{'background-color': option.color}"
                                :label="option.abbreviation"
                                shape="square"/>
                    </div>
                    <p>{{ option.name }}</p>
                </div>
            </template>
            <template #empty>
                {{ trans('app.member_user.empty') }}
            </template>
        </AutoComplete>
        <a href="#" @click.prevent="member.profession=null"
           v-if="member?.profession"
           class="text-sm text-red-500 hover:underline">
            {{ trans('app.member_profession.detach') }}
        </a>

        <template #footer>
            <Button type="button" severity="secondary" outlined @click="$emit('close')">
                {{ trans('actions.cancel') }}
            </Button>
            <Button type="button" @click="editMember" :loading="loading">
                {{ trans('actions.save') }}
            </Button>
        </template>
    </Dialog>
</template>

<style scoped lang="scss">

</style>
