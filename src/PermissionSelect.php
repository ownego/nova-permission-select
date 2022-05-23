<?php

namespace Ownego\PermissionSelect;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\Permission\PermissionRegistrar;

class PermissionSelect extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'permission-select';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $permission_class = app(PermissionRegistrar::class)->getPermissionClass();
        $permissions = $permission_class::all();
        $this->withMeta(['permissions' => $permissions]);
    }

    /**
     * @param NovaRequest $request
     * @param string $requestAttribute
     * @param HasPermissions $model
     * @param string $attribute
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if (! $request->exists($requestAttribute)) {
            return;
        }

        $values = collect(json_decode($request[$requestAttribute], true))->toArray();

        $model->syncPermissions($values);
    }
}
