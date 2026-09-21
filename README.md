# CRUDdy by Design with Inertia + Nuxt UI

Companion repository for the free course [CRUDdy by Design with Inertia + Nuxt UI](https://designcoder.net) (link to follow). It is a small podcast app on Laravel, Inertia, Vue and Nuxt UI where every action is one of the seven resource verbs (`index`, `show`, `create`, `store`, `edit`, `update`, `destroy`). Each lesson adds one idea and one Exercise; the git history of `main` is curated so that every lesson is a runnable checkpoint you can check out, build, and test.

## Setup

```sh
composer setup   # installs PHP deps, creates .env and the SQLite database, migrates, seeds, builds assets
npm run dev      # Vite dev server; then open the app with `php artisan serve`
```

`composer setup` seeds five demo podcasts and nine episodes. Register an account, then open **Podcasts**.

Useful commands:

```sh
composer test        # Pint, PHPStan and Pest
npm run lint         # ESLint (antfu config)
npm run typecheck    # vue-tsc
php artisan route:list --except-vendor
```

## How checkpoints work

Every lesson has two tags: `lesson/NN-start` is where the Exercise begins and `lesson/NN-solution` is where it ends. Check out a start tag, work the Exercise, then compare with the solution:

```sh
git checkout lesson/03-start
# ...work the exercise...
git diff lesson/03-solution
```

Each tag passes `composer test`, `npm run build`, `npm run lint` and `npm run typecheck`. After checking out a tag, run `php artisan migrate` (lessons 3, 4 and 5 add a migration) and `npm run build`. Dependencies are the same at every tag.

## Tags

| Lesson | Start | Solution | Exercise |
| --- | --- | --- | --- |
| 1. The seven verbs | `lesson/01-start` | `lesson/01-solution` | Name the verb of every route in `php artisan route:list` (reading lesson; both tags point at the starter app) |
| 2. Podcasts and episodes are resources | `lesson/02-start` | `lesson/02-solution` | Add `EpisodesController@show` |
| 3. The cover image is its own resource | `lesson/03-start` | `lesson/03-solution` | Add `PodcastCoverImageController@destroy` |
| 4. Subscribing is a pivot, not a verb | `lesson/04-start` | `lesson/04-solution` | Build `SubscriptionsController@index` |
| 5. Published is a state | `lesson/05-start` | `lesson/05-solution` | Add `CompletedEpisodesController` |
| 6. Make the old thing CRUDdy | `lesson/06-start` | `lesson/06-solution` | Refactor settings from `ProfileController@updateProfileInformation` / `@deleteUser` to `profile.update` and `account.destroy` (the start tag is the lesson 5 solution; lessons 1 to 5 carry the pre-refactor settings on purpose) |
| 7. When the model grows, the verbs don't | `lesson/07-start` | `lesson/07-solution` | Reading lesson; both tags point at the lesson 6 solution |

## License

MIT for the code. The course text lives elsewhere and is all rights reserved.
