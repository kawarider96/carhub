You are an expert Laravel architect. 
Generate full Laravel 12 API Resource classes for the following Eloquent models:
User, CarBrand, CarModel, FavoriteCar, CarImage, UserRequest.

RULES:
1. Use Laravel API Resources (JsonResource).
2. Follow REST best practices.
3. Each Resource must return ONLY the correct properties that exist in the model.
4. Include nested relationships when relevant:
   - FavoriteCarResource must include `model` (CarModelResource) and `images` (CarImageResource).
   - CarModelResource must include `brand` (CarBrandResource).
   - UserRequestResource must include `user` (UserResource) and optionally `handler` if exists.
5. Ensure null-safe output for optional relations.
6. Dates must be formatted as ISO 8601 (`$this->created_at?->toISOString()`).
7. CarImageResource must Base64-encode the binary content.
8. Use type-safe return signatures.
9. Do NOT include write/update logic—resources are for output only.
10. Return arrays with snake_case keys exactly matching the database schema.
11. Include `@OA` annotations inside each Resource for OpenAPI compatibility.

For each resource, generate:
✔ The PHP class  
✔ The namespace: App\Http\Resources  
✔ Use strict_types=1  
✔ The full `toArray()` method  
✔ OpenAPI docblock above the class (schema definition)

At the end, generate:
- A "Resource Index" showing which endpoints should use which Resource.
