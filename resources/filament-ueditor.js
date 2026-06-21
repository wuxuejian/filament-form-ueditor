window.FilamentUeditor = {
    instances: {},

    create(id, options = {}) {
        if (this.instances[id]) {
            return this.instances[id];
        }

        const editor = UE.getEditor(id, options);

        this.instances[id] = editor;

        return editor;
    },

    destroy(id) {
        const editor = this.instances[id];

        if (!editor) {
            return;
        }

        editor.destroy();

        delete this.instances[id];
    }
};

document.addEventListener('alpine:init', () => {
    Alpine.data('ueditorComponent', config => ({
        state: config.state,

        editor: null,

        init() {
            this.$nextTick(() => {
                this.editor = FilamentUeditor.create(
                    config.editorId,
                    config.options??{},
                    // {
                    //     serverUrl: config.uploadUrl,
                    //     autoHeightEnabled: config.autoHeightEnabled,
                    //     initialFrameHeight: config.initialFrameHeight,
                    // }
                );

                this.editor.ready(() => {
                    this.editor.setContent(this.state ?? '');

                    this.editor.addListener('contentChange', () => {
                        this.state = this.editor.getContent();
                    });
                });
            });

            this.$watch('state', value => {
                if (!this.editor) {
                    return;
                }

                const current = this.editor.getContent();

                if (current !== value) {
                    this.editor.setContent(value ?? '');
                }
            });
        },

        destroy() {
            FilamentUeditor.destroy(config.editorId);
        },
    }));
});
