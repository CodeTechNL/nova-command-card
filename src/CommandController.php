<?php

namespace Codetech\CommandCard;

use App\Jobs\SyncShop\SyncBrands;
use App\Jobs\SyncShop\SyncFilters;
use App\Jobs\SyncShop\SyncFilterValues;
use App\Jobs\SyncShop\SyncLanguages;
use App\Jobs\SyncShop\SyncProducts;
use App\Models\ShopCommand;
use App\Providers\AppServiceProvider;
use CodeTechNL\WebshopSetup\Contracts\BrandContract;
use CodeTechNL\WebshopSetup\Contracts\FilterContract;
use CodeTechNL\WebshopSetup\Contracts\LanguageContract;
use CodeTechNL\WebshopSetup\Models\AbstractModel;
use Illuminate\Routing\Controller;
use Laravel\Nova\Notifications\NovaNotification;

class CommandController extends Controller
{
    /**
     * @var array|string[]
     */
    protected array $resourceJobs = [
        'products' => SyncProducts::class,
        'brands' => SyncBrands::class,
        'filters' => SyncFilters::class,
        'filter_values' => SyncFilterValues::class,
        'languages' => SyncLanguages::class
    ];

    protected array $contracts = [
        'brands' => BrandContract::class,
        'filters' => FilterContract::class,
        'languages' => LanguageContract::class
    ];

    /**
     * @param ExecuteCommandRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ExecuteCommandRequest $request)
    {
        $resource = AppServiceProvider::getResource($request->getResource());
        /** @var AbstractModel $abstractModel */
        $abstractModel = app($resource['concrete']);

        (new ShopCommand())->registerResource($abstractModel, $request->getShopId());

        dispatch(new $resource['job']($request->getShopId(), $request->getResource()));

        $request->user()->notify(
            NovaNotification::make()
                ->message('Syncing ' . $request->getResource() . ' started!')
                ->icon('cog')
                ->type('info')
        );

        return response()->json([
            'status' => 'success'
        ]);
    }
}