# Isolation multi-tenant (checkinHub)

Chaque hôtel opère sur ses propres données. Deux hôtels peuvent avoir des
informations identiques (même nom de type, même numéro de chambre, même client)
sans que cela perturbe l'autre.

## Mécanisme

- **Filtrage des données** : le trait `App\Models\Concerns\BelongsToHotel`
  applique un `HotelScope` global (filtre `hotel_id = hôtel courant`) et
  renseigne `hotel_id` à la création. L'hôtel courant est résolu par
  `App\Support\TenantManager` depuis `auth()->user()->hotel_id`.

- **Modèles scopés** : Room, Type, Facility, Image, Customer, Transaction,
  TransactionExtra, Booking, Payment, CashierSession, CashierTransaction,
  RestaurantOrder, RestaurantOrderItem, RestaurantReservation, Menu, Category,
  FloorPlan.

## Règles de validation `unique` / `exists`

Les règles Laravel `unique:` et `exists:` interrogent la base **sans passer par
le scope Eloquent**. Elles doivent donc être scellées à l'hôtel explicitement,
sinon un hôtel bloque/voit les données d'un autre.

Scopées par hôtel :
- `types.name` (StoreTypeRequest)
- `rooms.number` (StoreRoomRequest, UpdateRoomRequest)
- `type_id` d'une chambre → doit appartenir à l'hôtel (exists scopé)
- `room_id` d'une transaction → doit appartenir à l'hôtel (exists scopé)

## Exceptions volontaires (NON scopées)

- **`users`** : l'email est **globalement unique** (identifiant de connexion du
  personnel). Un client (rôle Customer) a une **fiche `customers` scopée** ; son
  compte `users` est **optionnel** et créé uniquement si l'email est libre, donc
  le même client peut exister dans plusieurs hôtels (`customers.user_id`
  nullable).
- **`subscriptions`** : global, car le Super-Admin plateforme a besoin d'une vue
  transverse (revenus, renouvellements de tous les hôtels).
- **`room_statuses`** : états de workflow câblés en dur, **partagés** par tous.

## Verrouillage des modules par offre

Indépendant de l'isolation : `EnsurePlanModule` (`plan.module:<module>`) bloque
restaurant / housekeeping / rapports selon `config('plans.tiers.*.modules')`.
