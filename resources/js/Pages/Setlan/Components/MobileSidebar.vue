<script setup lang="ts">
import { computed } from "vue";
import CompanyIcon from "@/Components/Icons/CompnayIcon.vue";
import SidebarItem from "./SidebarItem.vue";
import AddItem from "@/Components/Icons/AddItem.vue";
import SecureIcon from "@/Pages/Setlan/Icons/SecureIcon.vue";
import { basicStore } from "@/store/basicStrore";
import { menu } from "@/Pages/Setlan/Helper";

const basicStoreInfo = basicStore();

const toggleSidebar = () => {
    basicStoreInfo.showSideBar = !basicStoreInfo.showSideBar;
};

const showSideBar = computed(() => basicStoreInfo.showSideBar);
</script>

<template>
    <!-- mobile side bar -->
    <div class="fixed bg-gray-600 h-screen w-full md:hidden transition-all duration-500 ease-in-out z-50"
        :class="[showSideBar ? '-top-[1px] sm:70px block' : '-top-[900px]']">
        <nav class="mt-[90px] flex flex-col h-1/3">
            <SidebarItem :to="menu.dashboard || '#'" @click="toggleSidebar" class="w-60">
                <CompanyIcon class="w-12 h-12 fill-blue-500 text-light-grey mt-2" />
                <span class="sidebar-item text-gray-100">Dashboard </span>
            </SidebarItem>

            <SidebarItem :to="menu.barang || '#'" @click="toggleSidebar" class="w-60">
                <AddItem class="w-[34px] h-[34px] ml-2 text-light-grey -mt-2" />
                <span class="sidebar-item text-gray-100 -mt-2 ml-2">Barang </span>
            </SidebarItem>

            <SidebarItem :to="menu.akuntansi || '#'" @click="toggleSidebar" class="w-60 pt-3">
                <SecureIcon class="w-[32px] h-[32px] ml-2 fill-blue-500 text-light-grey" />
                <span class="sidebar-item text-gray-100 ml-2">Akuntansi</span>
            </SidebarItem>
        </nav>
    </div>
    <!-- /mobile side bar -->
</template>
