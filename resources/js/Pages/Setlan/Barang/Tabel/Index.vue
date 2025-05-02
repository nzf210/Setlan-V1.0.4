<script setup lang="ts">
import PencilSquare from "@/Pages/Setlan/Icons/PencilSquare.vue";
import Trash from "@/Pages/Setlan/Icons/Trash.vue";
import { Barang as InterfaceBarang } from "@/Pages/Setlan/Helper";
import { ElMessageBox } from "element-plus";
import { masterBarangStore } from "@/store/MasterBarangStore";

const brgStore = masterBarangStore();

const { brgs } = defineProps<{
  brgs: {
    data: InterfaceBarang[];
    meta: any;
  };
  categories: any[];
}>();

const handleClose = (done: () => void) => {
  ElMessageBox.confirm("Apa anda yakin meninggalkan form ini ?")
    .then(() => {
      brgStore.editMode = false;
      done();
    })
    .catch(() => {
      // catch error
    });
};
</script>

<template>
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="table-container z-30">
      <!-- Dialog -->
      <el-dialog
        v-model="brgStore.dialogVisible"
        :title="brgStore.editMode ? 'Edit Barang' : 'Tambah Barang'"
        width="500"
        :before-close="handleClose"
        class="z-10"
      >
        <div>
          <form
            @submit.prevent="
              brgStore.editMode ? brgStore.editBarang() : brgStore.addBarang()
            "
            class="max-w-md mx-auto"
          >
            <div class="relative z-0 w-full mb-5 group">
              <input
                v-model="brgStore.nama_barang"
                type="text"
                name="floating_title"
                :id="'nama' + brgStore.id_barang"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" "
                required
              />
              <label
                :for="'nama' + brgStore.id_barang"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                >Nama Barang</label
              >
            </div>
            <div class="relative z-0 w-full mb-5 group">
              <input
                v-model="brgStore.harga"
                type="number"
                name="floating_price"
                :id="'harga' + brgStore.id_barang"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=""
                required
                step="0.01"
              />
              <label
                :for="'merek' + brgStore.id_barang"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                >Harga</label
              >
            </div>
            <div class="relative z-0 w-full mb-5 group">
              <input
                v-model="brgStore.merek"
                type="text"
                name="merek"
                :id="'merek' + brgStore.id_barang"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" "
                required
              />
              <label
                :for="'merek' + brgStore.id_barang"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                >Merek</label
              >
            </div>
            <div class="relative z-0 w-full mb-5 group">
              <label
                for="category"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Select Category
              </label>
              <!-- <select v-model="brgStore.category" id="category"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                           <option v-for="ctg in categories" :key="ctg.id" :value="ctg.id" selected>
                               {{ ctg.name }}
                           </option>
                       </select> -->
            </div>

            <!-- end -->
            <button
              type="submit"
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >
              Simpan
            </button>
          </form>
        </div>
      </el-dialog>
      <!-- Dialog -->
      <table
        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
      >
        <thead
          class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
        >
          <tr class="border-b-4 bg-gray-300">
            <th scope="col" class="p-4">
              <div class="flex items-center">
                <input
                  id="checkbox-all-search"
                  type="checkbox"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                />
                <label for="checkbox-all-search" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3">Nama Barang</th>
            <th scope="col" class="px-6 py-3">Merek</th>
            <th scope="col" class="px-6 py-3">Type Barang</th>
            <th scope="col" class="px-6 py-3">Category</th>
            <th scope="col" class="px-6 py-3">Harga</th>
            <th scope="col" class="px-6 py-3">Tahun Pembelian</th>
            <th scope="col" class="px-6 py-3">Tahun Pembuatan</th>
            <th scope="col" class="px-6 py-3">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-if="brgs.data.length"
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
            v-for="brg in brgs.data"
            :key="brg.id_barang"
          >
            <td class="w-4 p-4">
              <div class="flex items-center" :key="brg.id_barang">
                <input
                  :id="brg.id_barang ?? ''"
                  type="checkbox"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                />
                <label :for="brg.id_barang ?? ''" class="sr-only">checkbox</label>
              </div>
            </td>
            <th
              scope="row"
              class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
            >
              {{ brg.nama_barang }}
            </th>
            <td class="px-6 py-4">
              {{ brg.merek }}
            </td>
            <td class="px-6 py-4">
              {{ brg.type }}
            </td>
            <td class="px-6 py-4">
              {{ brg.category_id }}
            </td>
            <td class="px-6 py-4">
              {{ brg.harga }}
            </td>

            <td class="px-6 py-4">
              {{ brg.tahun_buat }}
            </td>
            <td class="flex items-center px-6 py-4">
              <button
                @click="brgStore.editBarangDialog(brg)"
                v-b-tooltip.hover
                title="Ubah"
                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
              >
                <PencilSquare class="w-5 h-5" />
              </button>
              <button
                @click="brgStore.deleteBarang(brg.id_barang)"
                v-b-tooltip.hover
                title="Hapus"
                class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3"
              >
                <Trash class="w-5 h-5" />
              </button>
            </td>
          </tr>
          <tr v-else>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="">
              <div class="mx-auto text-center align-middle items-center py-4">
                Tidak di temukan data Barang
              </div>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
