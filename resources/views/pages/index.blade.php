<x-Layout>

    <h2 class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3">Welcome To Workopia</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @forelse ($jobs as $job)
            <x-job-card :job="$job" />
        @empty
            <p>No jobs available at the moment.</p>
        @endforelse
    </div>

    <a href="{{ route('jobs.index') }}" class="flex items-center justify-center gap-2 text-xl">
        <i class="fa fa-arrow-alt-circle-right" aria-hidden="true"></i>
        Show All Jobs
    </a>

    <x-bottom-banner />
</x-Layout>
