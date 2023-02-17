<template>
  <DefaultField :field="field" :errors="errors">
    <template #field>
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
              <CheckboxWithLabel
                :name="child.name"
                @input="toggle($event, group, child)"
                :checked="child.checked"
              >
                {{ child.name }}
              </CheckboxWithLabel>
            </li>
          </ul>
        </li>
      </ul>
    </template>
  </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || {}
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
              const selectedPermission = _(this.value).find(v => {
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

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      const selectedPermissions = _(this.value).flatMap(({name, children}) => {
        return children;
      })
        .filter('checked')
        .map('value')
        .value();

      formData.append(this.field.attribute, JSON.stringify(selectedPermissions))
    },

    toggle(event, group, child) {
      const selectedGroup = _(this.value).find(g => g.name === group.name);
      const selectedChild = _(selectedGroup.children).find(c => c.name === child.name);
      selectedChild.checked = event.target.checked;
    },
  },
}
</script>
