<?php

// Trait NotificationHandler — Lógica de notificaciones y alertas
namespace App\Http\Traits;

use App\Models\Notification;

// Trait NotificationHandler
trait NotificationHandler
{
    // Procesar respuesta a solicitud de eliminación
    private function processNotificationResponse(Notification $notification, string $status): void
    {
        $notification->update([
            'status'  => $status,
            'read_at' => null,
        ]);

        $responseData = $this->buildNotificationResponseData($notification, $status);

        if ($responseData) {
            Notification::create(array_merge([
                'user_id'      => $notification->from_user_id,
                'from_user_id' => auth()->id(),
                'type'         => 'delete_request',
                'status'       => $status,
            ], $responseData));
        }
    }

    // Construir datos de respuesta según el tipo
    private function buildNotificationResponseData(Notification $notification, string $status): ?array
    {
        $actionText = $status === 'approved' ? 'APROBADA' : 'RECHAZADA';
        $canText    = $status === 'approved' ? 'Ahora puede eliminar' : 'No puede eliminar';

        $entityTypes = [
            'composting_id'    => [
                'model'   => \App\Models\Composting::class,
                'fk'      => 'composting_id',
                'label'   => fn($entity) => 'la pila de compostaje #' . $entity->formatted_pile_num,
            ],
            'machinery_id'     => [
                'model'   => \App\Models\Machinery::class,
                'fk'      => 'machinery_id',
                'label'   => fn($entity) => 'la maquinaria: ' . $entity->name,
            ],
            'maintenance_id'   => [
                'model'   => null,
                'fk'      => 'maintenance_id',
                'label'   => fn($id) => 'el control de actividades #' . str_pad($id, 3, '0', STR_PAD_LEFT),
            ],
            'supplier_id'      => [
                'model'   => null,
                'fk'      => 'supplier_id',
                'label'   => fn($id) => 'el proveedor #' . str_pad($id, 3, '0', STR_PAD_LEFT),
            ],
            'usage_control_id' => [
                'model'   => null,
                'fk'      => 'usage_control_id',
                'label'   => fn($id) => 'el control de uso del equipo #' . str_pad($id, 3, '0', STR_PAD_LEFT),
            ],
            'organic_id'       => [
                'model'   => null,
                'fk'      => 'organic_id',
                'label'   => fn($id) => 'el registro #' . str_pad($id, 3, '0', STR_PAD_LEFT),
            ],
        ];

        foreach ($entityTypes as $field => $config) {
            $entityId = $notification->$field;
            if (!$entityId) continue;

            if ($config['model']) {
                $entity = $config['model']::find($entityId);
                if (!$entity) continue;
                $label = ($config['label'])($entity);
            } else {
                $label = ($config['label'])($entityId);
            }

            return [
                $config['fk'] => $entityId,
                'message'     => "Su solicitud de eliminación ha sido {$actionText}. {$canText} {$label}",
            ];
        }

        return null;
    }
}
