<script lang="ts" setup>
import { computed, onBeforeUpdate, onMounted, ref } from "vue";
import {
    Document,
    Menu as IconMenu,
    Shop,
    DArrowLeft,
    DArrowRight,
    InfoFilled,
} from "@element-plus/icons-vue";
import ChevronLeftIcon from "@/Components/Icons/ChevronLeftIcon.vue";
import ChevronRightIcon from "@/Components/Icons/ChevronRightIcon.vue";
import { basicStore } from "@/store/basicStrore";
import { useMenuStore } from "@/store/menuStore";
import Bookmark from "@/Pages/Setlan/Icons/Bookmark.vue";
import { ElMenu, ElIcon, ElMenuItem, ElMenuItemGroup, ElSubMenu } from "element-plus";

const basicStoreInfo = basicStore();
const menu_strore = useMenuStore();
const isCollapse = computed(() => basicStoreInfo.showSideBar || false);
const active = ref<string>("1");

const setCollapse = (e: boolean) => {
    basicStoreInfo.tooggleSideBar(e);
};

const menuClick = (e: string) => {
    active.value = e;
    localStorage.setItem("active", e);
};

onMounted(() => {
    active.value = localStorage.getItem("active") || "1";
});

onBeforeUpdate(() => {
    console.info("On Before Update menu");
});
onMounted(() => {
    console.info("Elemen DOM:");
});
</script>

<template>
    <div class="left-0 hidden sm:block" key="sub-sidebar">
        <el-menu class="el-menu-vertical overflow-y-auto pt-4"
            :class="isCollapse ? 'min-h-[86vh]' : 'w-60 min-h-[86vh] max-h-[86vh]'" :collapse="isCollapse"
            :default-openeds="[active]" :default-active="active" :unique-opened="true" @select="menuClick"
            :collapse-transition="true" :hide-timeout="10" :show-timeout="10" :close-on-click-outside="false">
            <div index="icon" class="flex items-center">
                <el-icon v-if="isCollapse" class="mr-auto cursor-pointer w-8 h-8 ml-6 text-slate-800">
                    <ChevronRightIcon @click="setCollapse(false)" class="text-slate-800" />
                </el-icon>
                <el-icon v-else class="ml-auto cursor-pointer w-8 h-8 mr-4 text-slate-800">
                    <ChevronLeftIcon @click="setCollapse(true)" class="text-slate-800" />
                </el-icon>
            </div>
            <el-menu-item index="1">
                <el-icon><icon-menu /></el-icon>
                <template #title>Dashboard</template>
            </el-menu-item>
            <el-sub-menu index="2" :teleport="false" :teleported="false" :hide-timeout="330" :show-timeout="4440">
                <template #title>
                    <el-icon>
                        <shop />
                    </el-icon>
                    <span>Barang</span>
                </template>
                <el-menu-item index="21">
                    <template #title>
                        <el-icon>
                            <shop style="width: 15px; height: 15px" />
                        </el-icon>
                        <span>Master Barang</span>
                    </template></el-menu-item>
                <el-menu-item-group>
                    <template #title>Mutasi</template>
                </el-menu-item-group>
                <el-sub-menu index="22">
                    <template #title>
                        <el-icon><dArrow-left style="width: 15px; height: 15px" /></el-icon>
                        <span>Mutasi Masuk</span>
                    </template>
                    <el-menu-item index="23">
                        <template #title>
                            <el-icon>
                                <shop style="width: 10px; height: 10px" />
                            </el-icon>
                            <span>Draft</span>
                        </template>
                    </el-menu-item>
                    <el-menu-item index="20"><template #title>
                            <el-icon>
                                <shop style="width: 10px; height: 10px" />
                            </el-icon>
                            <span>Saldo Awal</span>
                        </template></el-menu-item>
                    <el-menu-item index="24"><template #title>
                            <el-icon>
                                <shop style="width: 10px; height: 10px" />
                            </el-icon>
                            <span>Barang Masuk</span>
                        </template></el-menu-item>
                    <el-menu-item index="25"><template #title>
                            <el-icon>
                                <shop style="width: 10px; height: 10px" />
                            </el-icon>
                            <span>Reklas << </span>
                        </template></el-menu-item>
                </el-sub-menu>
                <el-sub-menu index="26">
                    <template #title>
                        <el-icon><dArrow-right style="width: 15px; height: 15px" /></el-icon>
                        <span>Mutasi Keluar</span></template>
                    <el-menu-item index="27"><template #title>
                            <el-icon>
                                <shop style="width: 10px; height: 10px" />
                            </el-icon>
                            <span>Draft</span>
                        </template></el-menu-item>
                    <el-menu-item index="28"><template #title>
                            <el-icon>
                                <shop style="width: 10px; height: 10px" />
                            </el-icon>
                            <span>Barang Keluar</span>
                        </template>
                    </el-menu-item>
                    <el-menu-item index="29"><template #title>
                            <el-icon>
                                <shop style="width: 10px; height: 10px" />
                            </el-icon>
                            <span>Reklas >></span>
                        </template></el-menu-item>
                </el-sub-menu>
                <el-menu-item-group>
                    <template #title>Stok</template>
                </el-menu-item-group>
                <el-sub-menu index="221">
                    <template #title>
                        <el-icon>
                            <Bookmark />
                        </el-icon>
                        <span>Stok Barang</span>
                    </template>
                    <el-menu-item index="222"><template #title>
                            <el-icon>
                                <shop style="width: 10px; height: 10px" />
                            </el-icon>
                            <span>Stock Barang</span>
                        </template></el-menu-item>
                    <el-menu-item index="223"><template #title>
                            <el-icon>
                                <shop style="width: 10px; height: 10px" />
                            </el-icon>
                            <span>Expired / Rusak</span>
                        </template></el-menu-item>
                </el-sub-menu>
            </el-sub-menu>
            <el-sub-menu index="3">
                <template #title>
                    <el-icon>
                        <document />
                    </el-icon>
                    <span>Akuntansi</span>
                </template>
                <el-menu-item index="31">
                    <template #title>
                        <el-icon>
                            <document style="width: 15px; height: 15px" />
                        </el-icon>
                        <span> List Akun Aktif</span>
                    </template>
                </el-menu-item>
                <el-menu-item index="32"><template #title>
                        <el-icon>
                            <document style="width: 15px; height: 15px" />
                        </el-icon>
                        <span>Kebijakan Akuntansi</span>
                    </template>
                </el-menu-item>
            </el-sub-menu>
            <el-menu-item index="4">
                <el-icon><info-filled /></el-icon>
                <template #title>Tentang</template>
            </el-menu-item>
        </el-menu>
    </div>
</template>
