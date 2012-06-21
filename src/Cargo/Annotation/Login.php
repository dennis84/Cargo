<?php

namespace Cargo\Annotation;

/**
 * Login Annotation.
 *
 * @Login({
 *   check_path="/admin/login_check",
 *   logout_path="/logout",
 *   users={"ROLE_ADMIN", "123"}
 * })
 *
 * @Annotation
 */
class Login
{
}
