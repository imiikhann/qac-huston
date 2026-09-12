<div class="flex items-center gap-1">
    <x-filament::icon-button
        icon="heroicon-o-arrow-path"
        label="Clear cache"
        tooltip="Clear server &amp; browser cache"
        color="gray"
        :disabled="$clearing"
        wire:click="clear"
    />

    @assets
        <script>
            document.addEventListener('cache-cleared', () => {
                setTimeout(() => window.location.reload(), 1500);
            });
        </script>
    @endassets
</div>