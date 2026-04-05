<x-Layout>
    <h1>Available Jobs</h1>
    <ul>
        @forelse ($jobs as $job)
            <li> <a href="{{ route('jobs.show', $job->id) }}">{{ $job->title }}</a> - {{ $job->description }}</li>
        @empty
            <p>No jobs available at the moment.</p>
        @endforelse
    </ul>
</x-Layout>
