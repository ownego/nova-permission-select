<template>
  <div>
    <span title="Create" class="p-1">
      <span
        class="inline-block rounded-full w-2 h-2"
        :class="getClass(calculateRatio('create'))"
      />
    </span>
    <span title="View" class="p-1">
      <span
        class="inline-block rounded-full w-2 h-2"
        :class="getClass(calculateRatio('view'))"
      />
    </span>
    <span title="Edit" class="p-1">
      <span
        class="inline-block rounded-full w-2 h-2"
        :class="getClass(calculateRatio('edit'))"
      />
    </span>
    <span title="Delete" class="p-1">
      <span
        class="inline-block rounded-full w-2 h-2"
        :class="getClass(calculateRatio('delete'))"
      />
    </span>
  </div>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  methods: {
    calculateRatio(action) {
      const permissions = _(this.field.permissions).filter(p => p.name.includes(action)).value();
      const selectedPermissions = _(this.field.value).filter(p => p.name.includes(action)).value();

      if (!permissions.length) {
        return 0;
      }

      return selectedPermissions.length / permissions.length;
    },

    getClass(value) {
      if (value === 0) {
        return 'bg-danger';
      }

      if (value === 1) {
        return 'bg-success';
      }

      return 'bg-warning';
    },
  },
}
</script>
