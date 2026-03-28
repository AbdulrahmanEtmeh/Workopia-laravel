<x-Layout>
    <h1>Available Jobs</h1>
    <ul>
        @forelse ($jobs as $job)
            <li>{{ $job }}</li>
        @empty
            <p>No jobs available at the moment.</p>
        @endforelse
    </ul>
</x-Layout>
