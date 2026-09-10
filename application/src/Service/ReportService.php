<?php
namespace App\Service;

use App\Repository\TransactionRepository;
use Symfony\Contracts\Cache\CacheInterface;

use Symfony\Contracts\Cache\ItemInterface;

class ReportService {
    
    private const AVAILABLE_YEARS_CACHE_KEY = 'report_available_years';

    public function __construct(
        private TransactionRepository $transactionRepository,
        private CacheInterface $cache,
    ) {
    }

    public function getAvailableYears(): array
    {
        return $this->cache->get(
            self::AVAILABLE_YEARS_CACHE_KEY,
            function (ItemInterface $item) {
                $item->expiresAfter(3600);
                return range($this->transactionRepository->getMinYear(), (int)date('Y'));
            }
        );
    }

    public function clearAvailableYearsCache(): void
    {
        $this->cache->delete(self::AVAILABLE_YEARS_CACHE_KEY);
    }
}