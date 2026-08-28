<script setup lang="ts">
interface Props {
  meta?: ResponseMeta
  disabled?: boolean
}

defineProps<Props>();

const offset = defineModel<string, string, number, number>('offset', {
  default: "0",
  get: (input: string): number => parseInt(input),
  set: (value: number): string => `${value}`,
})

const limit = defineModel<string, string, number, number>('limit', {
  default: "20",
  get: (input: string): number => parseInt(input),
  set: (value: number): string => `${value}`,
})

const page = computed<number>({
  get: () => Math.ceil(offset.value / limit.value) + 1,
  set: (p) => offset.value = (p - 1) * limit.value,
})
</script>

<template>
  <div class="flex justify-end gap-4 items-center border-t border-default pt-4 px-4">
    <div v-if="meta" class="mr-auto">
      Показувати по:
      <USelect
        v-model="limit"
        :items="[10, 20, 50, 100]"
        @change="offset = 0"
      />
      |
      Записи {{ meta.offset + 1 }} - {{ Math.min(meta.offset + meta.limit, meta.total) }} із {{ meta.total }}
    </div>
    <UPagination
      :page
      :items-per-page="meta?.limit"
      :total="meta?.total"
      :disabled
      @update:page="page = $event"
    />
  </div>
</template>
