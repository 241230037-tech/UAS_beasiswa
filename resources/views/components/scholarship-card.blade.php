<div class="card h-100 shadow-sm border-0">

    <img
        src="{{ $image }}"
        class="card-img-top p-3"
        style="height:180px;object-fit:contain;"
        alt="{{ $title }}">

    <div class="card-body">

        <h6 class="fw-bold">
            {{ $title }}
        </h6>

        <p class="text-muted small mb-1">
            {{ $provider }}
        </p>

        <p class="small mb-1">
            📍 {{ $location }}
        </p>

        <p class="small mb-1">
            🎓 {{ $level }}
        </p>

        <p class="small text-danger">
            Deadline: {{ $deadline }}
        </p>

        <span class="badge bg-success">
            {{ $status }}
        </span>

    </div>

    <div class="card-footer bg-white border-0">

        <a
            href="{{ $link }}"
            target="_blank"
            class="btn btn-primary w-100">

            Lihat Detail

        </a>

    </div>

</div>