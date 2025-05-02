<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from "@/components/ui/collapsible";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Separator } from "@/components/ui/separator";
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarInset,
    SidebarMenu,
    SidebarMenuAction,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
    SidebarProvider,
    SidebarRail,
    SidebarTrigger,
} from "@/components/ui/sidebar";
import {
    AArrowUpIcon,
    AudioWaveform,
    BadgeCheck,
    Bell,
    BookOpen,
    Store,
    ChevronRight,
    ChevronsUpDown,
    Command,
    CreditCard,
    Folder,
    Forward,
    Frame,
    GalleryVerticalEnd,
    LogOut,
    Map,
    MoreHorizontal,
    PieChart,
    Settings2,
    Sparkles,
    DatabaseBackup,
    Building2,
    Asterisk,
} from "lucide-vue-next";
import { computed, ref, watch } from "vue";
import Navbar from "./Navbar.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { User as UserType } from "@/types";
import { useMenuStore } from "@/store/menuStore";

const page = usePage();
const currentRoute = ref(route().current());
const menuStrore = useMenuStore();
const isDisable = ref(false);
const props = defineProps<{
    user?: UserType;
    nama_kab?: string;
    nama_opd?: string;
    nama_unit?: string;
    year?: string;
    auth?: {
        role: string;
    };
}>();

const { tahun, namaKabupaten, namaOpd, namaUnit, namaUser } = menuStrore;

const roles = props.auth?.role !== "super_admin" && props.auth?.role !== "admin_kab";
console.log("props.auth?.role", props.auth?.role, roles);

watch(
    () => page.url,
    () => {
        currentRoute.value = route().current();
    }
);

const data: any = computed(() => ({
    user: {
        name: "Setlan",
        email: "Setlan@mail.com",
        avatar: "/avatars/setlan.jpg",
    },
    teams: [
        {
            name: "Aset Lancar",
            logo: GalleryVerticalEnd,
            plan: "Kabupaten",
        },
        {
            name: "Acme Corp.",
            logo: AudioWaveform,
            plan: "Startup",
        },
        {
            name: "Evil Corp.",
            logo: Command,
            plan: "Free",
        },
    ],
    navMain: [
        {
            title: "Dashboard",
            url: route("setlan.dashboard"),
            icon: Frame,
            isActive: currentRoute.value === "setlan.dashboard",
            items: [],
        },
        {
            title: "Barang",
            url: "#",
            icon: Store,
            isActive:
                currentRoute.value?.startsWith("setlan.barang.master") ||
                currentRoute.value?.startsWith("setlan.pengaturan.kodebarang"),
            items: [
                {
                    title: "Master Barang",
                    url: route("setlan.barang.master"),
                    isActive: currentRoute.value === "setlan.barang.master",
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Kode Barang",
                    url: route("setlan.pengaturan.kodebarang"),
                    isActive: currentRoute.value === "setlan.pengaturan.kodebarang",
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Pengaturan",
                    url: "#",
                    isActive: false,
                    icon: Asterisk,
                    isShow: isDisable,
                },
            ],
        },
        {
            title: "Persediaan",
            url: "#",
            icon: DatabaseBackup,
            isActive:
                currentRoute.value?.startsWith("setlan.barang.masuk") ||
                currentRoute.value?.startsWith("setlan.barang.saldo") ||
                currentRoute.value?.startsWith("barang.stock") ||
                currentRoute.value?.startsWith("barang.expired"),
            items: [
                {
                    title: "Draf Brg Masuk",
                    url: route("setlan.barang.masukdraft"),
                    isActive: route().current("setlan.barang.masukdraft"),
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Saldo Awal",
                    url: route("setlan.barang.saldoAwal"),
                    isActive: route().current("setlan.barang.saldoAwal"),
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Barang Masuk",
                    url: route("setlan.barang.masukbarang"),
                    isActive: route().current("setlan.barang.masukbarang"),
                    icon: Asterisk,
                    isShow: isDisable,
                },

                {
                    title: "Stock Barang",
                    url: route("barang.stock"),
                    isActive: route().current("barang.stock"),
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Rusak / Expiered",
                    url: route("barang.expired"),
                    isActive: route().current("barang.expired"),
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Opname Barang",
                    url: "#",
                    isActive: false,
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Reklas Masuk",
                    url: route("barang.stock"),
                    isActive: route().current("barang.stock"),
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Reklas Keluar",
                    url: route("barang.stock"),
                    isActive: route().current("barang.stock"),
                    icon: Asterisk,
                    isShow: isDisable,
                },
            ],
        },
        {
            title: "Berita Acara",
            url: "#",
            icon: BookOpen,
            items: [
                {
                    title: "Nota Pesanan",
                    url: "#",
                    isActive: false,
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "BAST",
                    url: "#",
                    isActive: false,
                    icon: Asterisk,
                    isShow: isDisable,
                },
            ],
        },
        {
            title: "Akuntansi",
            url: "#",
            icon: PieChart,
            isActive: route().current("setlan.akuntansi.*"),
            items: [
                {
                    title: "Akun Belanja Aktif",
                    url: route("setlan.akuntansi.akun"),
                    isActive: route().current("setlan.akuntansi.akun"),
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Kebijakan Akuntansi",
                    url: route("setlan.akuntansi.kebijakan"),
                    isActive: route().current("setlan.akuntansi.kebijakan"),
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Master Akun Belanja",
                    url: route("setlan.akuntansi.kebijakan"),
                    isActive: route().current("setlan.akuntansi.kebijakan"),
                    icon: Asterisk,
                    isShow: isDisable,
                },
            ],
        },
        {
            title: "Kegiatan",
            url: "#",
            icon: Building2,
            isActive:
                route().current("setlan.kegiatan") ||
                route().current("setlan.subKegiatan") ||
                route().current("setlan.unitSubKeg"),
            items: [
                {
                    title: "Kegiatan",
                    url: route("setlan.kegiatan"),
                    isActive: route().current("setlan.kegiatan"),
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Sub Kegiatan",
                    url: route("setlan.subKegiatan"),
                    isActive: route().current("setlan.subKegiatan"),
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Unit Sub Kegiatan",
                    url: route("setlan.unitSubKeg"),
                    isActive: route().current("setlan.unitSubKeg"),
                    icon: Asterisk,
                    isShow: isDisable,
                },
            ],
        },
        {
            title: "Pengaturan",
            url: "#",
            icon: Settings2,
            isActive: route().current("setlan.pengaturan.tahun"),
            items: [
                {
                    title: "Tahun",
                    url: route("setlan.pengaturan.tahun"),
                    isActive: route().current("setlan.pengaturan.tahun"),
                    icon: Asterisk,
                    isDisable: roles,
                },
                {
                    title: "Kegiatan",
                    url: "#",
                    isActive: false,
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Kode Barang",
                    url: "#",
                    isActive: false,
                    icon: Asterisk,
                    isShow: isDisable,
                },
                {
                    title: "Satuan",
                    url: "#",
                    isActive: false,
                    icon: Asterisk,
                    isShow: isDisable,
                },
            ],
        },
    ],
    projects: [
        {
            name: "Petunjuk Penggunaan",
            url: "#",
            icon: Map,
        },
    ],
}));

const activeTeam = ref(data.value?.teams[0] ?? {});

function setActiveTeam(team: typeof data.value.teams[number]) {
    activeTeam.value = team;
}
</script>

<template>
    <SidebarProvider>
        <Sidebar collapsible="icon">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton class="flex flex-row justify-between">
                            <span> Kabupaten {{ namaKabupaten }} </span>
                            <Separator orientation="vertical" class="h-4" />
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>
            <SidebarContent>
                <SidebarGroup>
                    <SidebarGroupLabel>Menu</SidebarGroupLabel>
                    <SidebarMenu>
                        <Collapsible v-for="item in data.navMain" :key="item.title" as-child
                            :default-open="item.isActive" class="group/collapsible">
                            <SidebarMenuItem>
                                <CollapsibleTrigger as-child>
                                    <SidebarMenuButton :tooltip="item.title">
                                        <component :is="item.icon" :class="item.isActive
                                                ? 'text-primary-primary'
                                                : 'text-muted-foregroundicon'
                                            " />
                                        <span v-if="item.items.length > 0"
                                            :class="item.isActive ? 'font-extrabold' : ''">{{ item.title }}</span>
                                        <Link :href="item.url" v-else :class="item.isActive ? 'font-extrabold' : ''">
                                        {{ item.title }}
                                        </Link>
                                        <ChevronRight v-if="item.items.length > 0"
                                            class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                                    </SidebarMenuButton>
                                </CollapsibleTrigger>
                                <template v-for="subItem in item.items" :key="subItem.title">
                                    <div v-if="!subItem.isDisable">
                                        <CollapsibleContent>
                                            <SidebarMenuSub>
                                                <SidebarMenuSubItem>
                                                    <SidebarMenuSubButton as-child>
                                                        <Link :href="subItem.url">
                                                        <component :is="subItem.icon" class="w-4 h-4" :class="subItem.isActive
                                                                ? 'text-primary'
                                                                : 'text-muted-foreground'
                                                            " />
                                                        <span :class="subItem.isActive
                                                                ? 'font-extrabold text-primary-primary'
                                                                : ''
                                                            ">
                                                            {{ subItem.title }}
                                                        </span>
                                                        </Link>
                                                    </SidebarMenuSubButton>
                                                </SidebarMenuSubItem>
                                            </SidebarMenuSub>
                                        </CollapsibleContent>
                                    </div>
                                </template>
                            </SidebarMenuItem>
                        </Collapsible>
                    </SidebarMenu>
                </SidebarGroup>
                <SidebarGroup class="group-data-[collapsible=icon]:hidden">
                    <SidebarGroupLabel>Pendukung</SidebarGroupLabel>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="item in data.projects" :key="item.name">
                            <SidebarMenuButton as-child>
                                <Link :href="item.url">
                                <component :is="item.icon" />
                                <span>{{ item.name }}</span>
                                </Link>
                            </SidebarMenuButton>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <SidebarMenuAction show-on-hover>
                                        <MoreHorizontal />
                                        <span class="sr-only">More</span>
                                    </SidebarMenuAction>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent class="w-48 rounded-lg" side="bottom" align="end">
                                    <DropdownMenuItem>
                                        <Folder class="text-muted-foreground" />
                                        <span>Pendahuluan</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <Forward class="text-muted-foreground" />
                                        <span>Memulai</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem>
                                        <AArrowUpIcon class="text-muted-foreground" />
                                        <span>Petunjuk Penggunaan</span>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </SidebarMenuItem>
                        <SidebarMenuItem>
                            <SidebarMenuButton class="text-sidebar-foreground/70">
                                <MoreHorizontal class="text-sidebar-foreground/70" />
                                <span>More</span>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroup>
            </SidebarContent>
            <SidebarFooter>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <SidebarMenuButton size="lg"
                                    class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground">
                                    <Avatar class="h-8 w-8 rounded-lg">
                                        <AvatarImage :src="data.user.avatar" :alt="data.user.name" />
                                        <AvatarFallback class="rounded-lg"> CN </AvatarFallback>
                                    </Avatar>
                                    <div class="grid flex-1 text-left text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ data.user.name }}</span>
                                        <span class="truncate text-xs">{{ data.user.email }}</span>
                                    </div>
                                    <ChevronsUpDown class="ml-auto size-4" />
                                </SidebarMenuButton>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg"
                                side="bottom" align="end" :side-offset="4">
                                <DropdownMenuLabel class="p-0 font-normal">
                                    <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                        <Avatar class="h-8 w-8 rounded-lg">
                                            <AvatarImage :src="data.user.avatar" :alt="data.user.name" />
                                            <AvatarFallback class="rounded-lg"> CN </AvatarFallback>
                                        </Avatar>
                                        <div class="grid flex-1 text-left text-sm leading-tight">
                                            <span class="truncate font-semibold">{{ data.user.name }}</span>
                                            <span class="truncate text-xs">{{ data.user.email }}</span>
                                        </div>
                                    </div>
                                </DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                <DropdownMenuGroup>
                                    <DropdownMenuItem>
                                        <Sparkles />
                                        Upgrade to Pro
                                    </DropdownMenuItem>
                                </DropdownMenuGroup>
                                <DropdownMenuSeparator />
                                <DropdownMenuGroup>
                                    <DropdownMenuItem>
                                        <BadgeCheck />
                                        Account
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <CreditCard />
                                        Billing
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <Bell />
                                        Notifications
                                    </DropdownMenuItem>
                                </DropdownMenuGroup>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem>
                                    <LogOut />
                                    Log out
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarFooter>
            <SidebarRail />
        </Sidebar>
        <SidebarInset>
            <div class="flex flex-row top-0 sticky">
                <div class="h-12 z-50 pt-0 flex flex-row">
                    <div class="flex flex-col h-14 mt-0 pt-0">
                        <SidebarTrigger class="mx-4 pt-2 hover:text-black size-6" />
                        <span class="m-auto text-sm font-semibold">{{ tahun }}</span>
                    </div>
                    <Separator orientation="vertical" class="h-4 text-cyan-100" />
                </div>
                <Navbar :opd="namaOpd" :kab="namaKabupaten" :unit="namaUnit" :user="namaUser" />
            </div>
            <slot :user="user" :nama_kab="nama_kab" :nama_opd="nama_opd" :nama_unit="nama_unit" :year="year" />
        </SidebarInset>
    </SidebarProvider>
</template>
