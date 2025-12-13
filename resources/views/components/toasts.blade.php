@props([
  'position' => 'toast-top toast-end',
  'duration' => 3000,
])

<div
  x-data="{
    toasts: [],
    add(type, message) {
      if (!message) return;
      const id = Date.now() + Math.random();
      this.toasts.push({ id, type, message });
      setTimeout(() => this.remove(id), {{ (int) $duration }});
    },
    remove(id) {
      this.toasts = this.toasts.filter(t => t.id !== id);
    }
  }"
  x-on:toast.window="add($event.detail.type ?? 'info', $event.detail.message ?? '')"
  class="toast {{ $position }} z-50 mx-8 pointer-events-none"
>
  <template x-for="t in toasts" :key="t.id">
    <div
      class="alert pointer-events-auto flex items-center gap-2"
      :class="{
        'alert-success': t.type === 'success',
        'alert-error':   t.type === 'error',
        'alert-warning': t.type === 'warning',
        'alert-info':    !['success','error','warning'].includes(t.type)
      }"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 translate-y-2"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 translate-y-2"
    >
      <!-- Inline SVGs directly in template -->
      <template x-if="t.type === 'error'">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </template>

      <template x-if="t.type === 'success'">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M5 13l4 4L19 7" />
        </svg>
      </template>

      <template x-if="t.type === 'warning'">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4h.01M12 5a7 7 0 100 14a7 7 0 000-14z" />
        </svg>
      </template>

      <template x-if="t.type === 'info'">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13 16h-1v-4h-1m1-4h.01M12 18a9 9 0 110-18a9 9 0 010 18z" />
        </svg>
      </template>

      <span x-text="t.message"></span>
      <button class="btn btn-ghost btn-xs ml-2" @click="remove(t.id)">✕</button>
    </div>
  </template>
</div>