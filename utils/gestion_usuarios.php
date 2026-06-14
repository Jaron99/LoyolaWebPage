<?php include_once __DIR__ . "/../controllers/admin.controller.php"; ?>
<!-- Gestión de Usuarios -->
<div class="tab-pane fade <?php echo ($active == 'usuarios') ? 'show active' : ''; ?>" id="vista-usuarios">
    <!-- Título -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">Gestión de Usuarios</h2>
            <p class="text-muted mb-0">Administre los accesos, contraseñas y roles del sistema.</p>
        </div>
        <!-- Botón para Agregar Nuevo Usuario -->
        <button class="btn text-white px-4 shadow-sm" style="background-color: var(--verde-institucional);" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Usuario
        </button>
    </div>
    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4 bg-white">
        <div class="card-body p-3">
            <form method="GET" action="admin.view.php">
                <input type="hidden" name="tab" value="usuarios">
                <div class="row g-2">
                    <div class="col-md-4">
                        <!-- Filtro por Rol -->
                        <select name="rol" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">Todos los Roles</option>
                            <?php
                            if (!empty($listaRoles)):
                                foreach ($listaRoles as $rol_db):
                                    $seleccionado = ($filtrorol == $rol_db) ? 'selected' : '';
                            ?>
                                    <option value="<?php echo htmlspecialchars($rol_db); ?>" <?php echo $seleccionado; ?>>
                                        <?php echo ucfirst(htmlspecialchars($rol_db)); ?>
                                    </option>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                    <!-- Filtro por Nombre de Usuario -->
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-light text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="busquedaUsuarios" class="form-control border-0 bg-light" placeholder="Buscar por nombre de usuario..." value="<?php echo htmlspecialchars($busquedaUsuarios); ?>">
                            <button type="submit" class="btn text-white px-4 shadow-sm" style="background-color: var(--verde-institucional);">Buscar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Tabla de Usuarios -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted" style="font-size: 0.85rem; text-transform: uppercase;">
                        <tr>
                            <th class="ps-4 py-3">Usuario / Acceso</th>
                            <th class="py-3">Rol en el Sistema</th>
                            <th class="py-3">Estado</th>
                            <th class="text-end pe-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $user): ?>
                                <tr>
                                    <!-- Nombre de Usuario -->
                                    <td class="ps-4 fw-bold text-dark">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3 text-secondary">
                                                <i class="bi bi-person-fill fs-5"></i>
                                            </div>
                                            <?php echo htmlspecialchars($user['usuario']); ?>
                                        </div>
                                    </td>
                                    <!-- Rol en el Sistema -->
                                    <td>
                                        <?php
                                        $rol = strtolower($user['rol'] ?? '');
                                        $claseBadge = 'bg-secondary';
                                        if ($rol == 'admin') $claseBadge = 'badge-admin';
                                        if ($rol == 'docente') $claseBadge = 'badge-profesor';
                                        if ($rol == 'estudiante') $claseBadge = 'badge-estudiante';
                                        ?>
                                        <span class="badge <?php echo $claseBadge; ?> rounded-pill px-3 py-2 text-uppercase" style="font-size: 0.75rem;">
                                            <?php echo htmlspecialchars($rol); ?>
                                        </span>
                                    </td>
                                    <!-- Estado del Usuario -->
                                    <td>
                                        <?php if ($user['estado'] == 1): ?>
                                            <span class="text-success small fw-bold">
                                                <i class="bi bi-circle-fill me-1"></i> Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="text-danger small fw-bold">
                                                <i class="bi bi-circle-fill me-1"></i> Bloqueado
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <!-- Acciones -->
                                    <td class="text-end pe-4">
                                        <!-- Boton para Editar Usuario -->
                                        <?php if (strtolower($rol) != 'admin'): ?>
                                            <button class="btn btn-sm btn-light text-primary me-1 shadow-sm" title="Editar Usuario" data-bs-toggle="modal" data-bs-target="#modalEditarU_<?php echo htmlspecialchars($user['usuario']); ?>">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                        <?php endif; ?>
                                        <!-- Botón para Editar Contraseña -->
                                        <button class="btn btn-sm btn-light text-primary me-1 shadow-sm" title="Editar Contraseña" data-bs-toggle="modal" data-bs-target="#modalEditar_<?php echo htmlspecialchars($user['usuario']); ?>">
                                            <i class="bi bi-key-fill"></i>
                                        </button>
                                        <!-- Formulario para bloquear/desbloquear usuario -->
                                        <?php if (strtolower($rol) != 'admin'): ?>
                                            <form method="POST" action="../controllers/usuario.controller.php" class="d-inline">
                                                <input type="hidden" name="accion" value="bloquear_usuario">
                                                <input type="hidden" name="usuarioBloquear" value="<?php echo $user['usuario']; ?>">
                                                <input type="hidden" name="nuevoEstado"
                                                    value="<?php echo ($user['estado'] == 1) ? '0' : '1'; ?>">

                                                <button type="submit"
                                                    class="btn btn-sm btn-light <?php echo ($user['estado'] == 1) ? 'text-warning' : 'text-success'; ?> me-1 shadow-sm"
                                                    title="<?php echo ($user['estado'] == 1) ? 'Bloquear Usuario' : 'Desbloquear Usuario'; ?>">

                                                    <i class="bi <?php echo ($user['estado'] == 1) ? 'bi-lock-fill' : 'bi-unlock-fill'; ?>"></i>
                                                </button>
                                            </form>
                                            <!-- Modal para Eliminar Usuario -->
                                            <form method="POST" action="../controllers/usuario.controller.php" class="d-inline">
                                                <input type="hidden" name="accion" value="eliminar_usuario">
                                                <input type="hidden" name="usuarioEliminar" value="<?php echo $user['usuario']; ?>">

                                                <button type="button" class="btn btn-sm btn-light text-danger shadow-sm" title="Eliminar Usuario" data-bs-toggle="modal" data-bs-target="#modalEliminar_<?php echo htmlspecialchars($user['usuario']); ?>">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border shadow-sm px-2 py-1" title="Cuenta protegida">
                                                <i class="bi bi-shield-lock-fill"></i>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <!-- Modal para Editar nombre de usuario -->
                                <div class="modal fade" id="modalEditarU_<?php echo htmlspecialchars($user['usuario']); ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header border-0 pb-0 pt-4 px-4 text-center">
                                                <h5 class="modal-title fw-bold text-dark w-100">
                                                    <i class="bi bi-key-fill me-2 text-primary"></i> Cambiar Usuario
                                                </h5>
                                                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form action="../controllers/usuario.controller.php" method="POST">
                                                    <input type="hidden" name="accion" value="editar_usuario">
                                                    <input type="hidden" name="usuarioeditar" value="<?php echo htmlspecialchars($user['usuario']); ?>">

                                                    <div class="mb-4 text-center pb-3 border-bottom">
                                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                                            <i class="bi bi-person-fill fs-2 text-secondary"></i>
                                                        </div>
                                                        <h6 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($user['usuario']); ?></h6>
                                                        <span class="badge bg-secondary mt-1 text-uppercase" style="font-size: 0.65rem;"><?php echo htmlspecialchars($rol); ?></span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-bold text-uppercase">Nuevo Nombre de Usuario</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-0"><i class="bi bi-person text-muted"></i></span>
                                                            <input type="text" name="nuevousuario" class="form-control bg-light border-0 p-2" placeholder="Nuevo nombre de usuario">
                                                        </div>
                                                    </div>
                                                    <div class="d-grid gap-2 mt-4">
                                                        <button type="submit" class="btn text-white shadow-sm" style="background-color: var(--verde-institucional);">
                                                            <i class="bi bi-save me-1"></i> Guardar Cambios
                                                        </button>
                                                        <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal para Editar Contraseña -->
                                <div class="modal fade" id="modalEditar_<?php echo htmlspecialchars($user['usuario']); ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header border-0 pb-0 pt-4 px-4 text-center">
                                                <h5 class="modal-title fw-bold text-dark w-100">
                                                    <i class="bi bi-key-fill me-2 text-primary"></i> Cambiar Clave
                                                </h5>
                                                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form action="../controllers/usuario.controller.php" method="POST">
                                                    <input type="hidden" name="accion" value="editar_password">
                                                    <input type="hidden" name="usuarioeditar" value="<?php echo htmlspecialchars($user['usuario']); ?>">

                                                    <div class="mb-4 text-center pb-3 border-bottom">
                                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                                            <i class="bi bi-person-fill fs-2 text-secondary"></i>
                                                        </div>
                                                        <h6 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($user['usuario']); ?></h6>
                                                        <span class="badge bg-secondary mt-1 text-uppercase" style="font-size: 0.65rem;"><?php echo htmlspecialchars($rol); ?></span>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-bold text-uppercase">Nueva Contraseña</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-muted"></i></span>
                                                            <input type="password" name="nuevacontraseña" class="form-control bg-light border-0 p-2" placeholder="Mínimo 4 caracteres" required minlength="4">
                                                        </div>
                                                    </div>

                                                    <div class="d-grid gap-2 mt-4">
                                                        <button type="submit" class="btn text-white shadow-sm" style="background-color: var(--verde-institucional);">
                                                            <i class="bi bi-save me-1"></i> Guardar Cambios
                                                        </button>
                                                        <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal de Confirmación para Eliminar Usuario -->
                                <div class="modal fade" id="modalEliminar_<?php echo htmlspecialchars($user['usuario']); ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-body p-4 text-center">
                                                <div class="text-danger mb-3">
                                                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i>
                                                </div>
                                                <h5 class="fw-bold text-dark mb-3">¿Eliminar Usuario?</h5>
                                                <p class="text-muted small mb-4">Está a punto de borrar permanentemente el acceso de <strong class="text-dark"><?php echo htmlspecialchars($user['usuario']); ?></strong>. Esta acción no se puede deshacer.</p>
                                                <form action="../controllers/usuario.controller.php" method="POST">
                                                    <input type="hidden" name="accion" value="eliminar_usuario">
                                                    <input type="hidden" name="usuarioEliminar" value="<?php echo htmlspecialchars($user['usuario']); ?>">
                                                    <div class="d-grid gap-2">
                                                        <button type="submit" class="btn btn-danger shadow-sm fw-bold">
                                                            Sí, Eliminar
                                                        </button>
                                                        <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <!-- Modal para Crear Nuevo Usuario -->
                            <div class="modal fade" id="modalNuevoUsuario" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">

                                        <div class="modal-header border-0 pb-0 pt-4 px-4">
                                            <h5 class="modal-title fw-bold text-dark">
                                                <i class="bi bi-person-plus-fill me-2 text-primary"></i> Agregar Nuevo Usuario
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body p-4">
                                            <form action="../controllers/usuario.controller.php" method="POST">
                                                <input type="hidden" name="accion" value="crear_usuario">

                                                <div class="mb-3">
                                                    <label class="form-label text-muted small fw-bold text-uppercase">Nombre de Usuario</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light border-0"><i class="bi bi-person text-muted"></i></span>
                                                        <input type="text" name="usuarioNuevo" class="form-control bg-light border-0 p-2" placeholder="Ej. juan.perez" required>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label text-muted small fw-bold text-uppercase">Contraseña Inicial</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-muted"></i></span>
                                                        <input type="password" name="nuevacontraseña" class="form-control bg-light border-0 p-2" placeholder="Mínimo 4 caracteres" required minlength="4">
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="form-label text-muted small fw-bold text-uppercase">Rol del Sistema</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light border-0"><i class="bi bi-shield-lock text-muted"></i></span>
                                                        <select name="rolNuevo" class="form-select bg-light border-0 p-2" required>
                                                            <option value="" selected disabled>Seleccione un rol...</option>
                                                            <option value="admin">Administrador</option>
                                                            <option value="docente">Docente</option>
                                                            <option value="estudiante">Estudiante</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                                                    <button type="button" class="btn btn-light text-muted px-4" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn text-white px-4 shadow-sm" style="background-color: var(--verde-institucional);">
                                                        <i class="bi bi-save me-1"></i> Guardar Usuario
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    No se encontraron usuarios registrados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>