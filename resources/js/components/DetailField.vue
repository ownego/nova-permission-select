<template>
  <PanelItem :index="index" :field="field">
    <template #value>
      <ul class="list-none">
        <li
          v-for="group in value"
          :key="group.name"
          class="mt-2"
        >
          <label><b>{{ group.name }}</b></label>
          <ul class="list-none ml-6">
            <li
              class="mt-2"
              v-for="child in group.children"
              :key="child.name"
            >
              <span
                class="inline-flex items-center py-1 pl-2 pr-3"
              >
                <Icon
                  viewBox="0 0 24 24"
                  width="24"
                  height="24"
                  :type="getType(child.checked)"
                  :class="getColor(child.checked)"
                />
                <span class="ml-1">{{ child.name }}</span>
              </span>
            </li>
          </ul>
        </li>
      </ul>
    </template>
  </PanelItem>
</template>

<script>
export default {
  props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],

  data: () => ({
    value: [],
    classes: {
      true: 'text-success-dark',
      false: 'text-danger-dark',
    },
  }),

  created() {
    const permissions = _(this.field.permissions);

    this.value = permissions
      .map(permission => {
        const spaceIndex = permission.name.indexOf(' ');
        name = permission.name.substring(spaceIndex + 1);

        return {
          name: name,
        };
      })
      .uniqBy('name')
      .map(resource => {
        const children = permissions
          .filter(permission => {
            const spaceIndex = permission.name.indexOf(' ');
            name = permission.name.substring(spaceIndex + 1);

            return name === resource.name;
          })
          .map(permission => {
            const name = permission.name.split(' ')[0];
            const selectedPermission = _(this.field.value).find(v => {
              return v.name === permission.name;
            })

            return {
              name: name,
              value: permission.name,
              checked: !!selectedPermission,
            };
          })
          .value();

        return {
          name: resource.name,
          children: children,
        };
      })
      .value();
  },

  methods: {
    getType(value) {
      return value ? 'check-circle' : 'x-circle';
    },

    getColor(value) {
      return value ? 'text-green-500' : 'text-red-500';
    },
  }
}
</script>
