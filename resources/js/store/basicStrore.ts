import { defineStore } from 'pinia';
import { ref } from 'vue';

export const basicStore = defineStore('basicStore', () => {
    const showSideBar = ref<any>(JSON.parse(localStorage.getItem("showSideBar")??'false') ?? false);
    function tooggleSideBar(e:boolean) {
      showSideBar.value = e;
      localStorage.setItem("showSideBar", JSON.stringify(showSideBar.value));
    }

    return { showSideBar, tooggleSideBar };
});
