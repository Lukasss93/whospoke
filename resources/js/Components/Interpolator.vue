<script>
import {h, defineComponent, ref} from 'vue';

export default defineComponent({
    props: ['message'],
    setup(props, { slots }) {
        const message = ref(props.message);
        const children = {};

        for (const slotName of Object.keys(slots).filter((key) => key !== '_')) {
            message.value = message.value.replace(`:${slotName}`, `<slot name="${slotName}" />`);
            children[slotName] = slots[slotName]();
        }

        return () => h({ template: message.value }, null, children);
    },
});
</script>
