<?php

namespace App\Services\Report;

use App\Services\Report\Contracts\ReportDataProvider;
use InvalidArgumentException;
use LogicException;

class ReportDataRegistry
{
    /** @var array<string, ReportDataProvider> */
    private array $providers = [];

    /**
     * @param iterable<ReportDataProvider> $providers
     */
    public function __construct(iterable $providers = [])
    {
        foreach ($providers as $provider) {
            $this->register($provider);
        }
    }

    public function register(ReportDataProvider $provider): static
    {
        $this->providers[$provider->key()] = $provider;

        return $this;
    }

    public function get(string $key): ReportDataProvider
    {
        return $this->providers[$key]
            ?? throw new InvalidArgumentException("Report data provider [{$key}] tidak terdaftar.");
    }

    /**
     * Daftar provider yang dipakai template.
     * Urutan: config['providers'] jika ada, jika tidak semua provider terdaftar.
     *
     * @return string[]
     */
    public function keysFor(array $templateConfig): array
    {
        $keys = $templateConfig['providers'] ?? array_keys($this->providers);

        foreach ($keys as $key) {
            $this->get($key); // validasi: lempar exception jika tidak terdaftar
        }

        return array_values($keys);
    }

    /**
     * Jalankan semua provider yang dipakai template, lalu merge hasilnya.
     */
    public function build(ReportContext $ctx): array
    {
        $data = [];

        foreach ($this->keysFor($ctx->template->configuration ?? []) as $key) {
            $provider = $this->get($key);

            if (! $provider->supports($ctx)) {
                continue;
            }

            foreach ($provider->build($ctx) as $binding => $value) {
                if (array_key_exists($binding, $data)) {
                    throw new LogicException(
                        "Binding [{$binding}] dari provider [{$key}] bentrok dengan provider lain."
                    );
                }
                $data[$binding] = $value;
            }
        }

        return $data;
    }

    /**
     * Schema gabungan untuk UI builder dan validasi template.
     *
     * @param string[]|null $keys null = semua provider
     */
    public function schema(?array $keys = null): array
    {
        $schema = [];

        foreach ($keys ?? array_keys($this->providers) as $key) {
            $provider = $this->get($key);
            $schema[$key] = [
                'label'    => $provider->label(),
                'bindings' => $provider->schema(),
            ];
        }

        return $schema;
    }

    /**
     * Daftar nama binding root yang valid (untuk validasi dataSource/binding).
     *
     * @return string[]
     */
    public function allowedRootBindings(?array $keys = null): array
    {
        return collect($this->schema($keys))
            ->flatMap(fn($p) => array_keys($p['bindings']))
            ->values()
            ->all();
    }
}