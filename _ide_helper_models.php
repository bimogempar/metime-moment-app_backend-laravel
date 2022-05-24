<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Features
 *
 * @property int $id
 * @property string $feature
 * @property int $status
 * @property int $project_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project|null $project
 * @method static \Database\Factories\FeaturesFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Features newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Features newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Features query()
 * @method static \Illuminate\Database\Eloquent\Builder|Features whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Features whereFeature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Features whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Features whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Features whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Features whereUpdatedAt($value)
 */
	class Features extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Progress
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $description
 * @property string|null $img_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ProgressFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Progress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Progress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Progress query()
 * @method static \Illuminate\Database\Eloquent\Builder|Progress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Progress whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Progress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Progress whereImgUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Progress whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Progress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Progress whereUserId($value)
 */
	class Progress extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property int $id
 * @property string $client
 * @property string $slug
 * @property string $date
 * @property string $time
 * @property string $location
 * @property int $status
 * @property string $phone_number
 * @property string|null $img
 * @property string|null $folder_gdrive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Features[] $features
 * @property-read int|null $features_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Progress[] $progress
 * @property-read int|null $progress_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\ProjectFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereFolderGdrive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TokenInitialPassword
 *
 * @property int $id
 * @property int $user_id
 * @property string $token_initial_password
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $users
 * @method static \Database\Factories\TokenInitialPasswordFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenInitialPassword newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenInitialPassword newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenInitialPassword query()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenInitialPassword whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenInitialPassword whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenInitialPassword whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenInitialPassword whereTokenInitialPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenInitialPassword whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenInitialPassword whereUserId($value)
 */
	class TokenInitialPassword extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $no_hp
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property int $role
 * @property string|null $img
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TokenInitialPassword|null $TokenInitialPassword
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Progress[] $progress
 * @property-read int|null $progress_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

