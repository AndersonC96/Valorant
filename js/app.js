(() => {
    const navToggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('#main-nav');

    if (navToggle && nav) {
        navToggle.addEventListener('click', () => {
            const expanded = navToggle.getAttribute('aria-expanded') === 'true';
            navToggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            nav.classList.toggle('is-open');
        });
    }

    const appRoot = document.querySelector('[data-collection-app]');
    if (!appRoot) {
        return;
    }

    const controls = document.querySelector('[data-controls]');
    const status = appRoot.querySelector('[data-status]');
    const grid = appRoot.querySelector('[data-grid]');
    const pagination = appRoot.querySelector('[data-pagination]');
    const title = document.querySelector('[data-title]');
    const description = document.querySelector('[data-description]');

    const state = {
        type: appRoot.getAttribute('data-initial-type') || 'agents',
        page: 1,
        totalPages: 1,
        pageSize: Number(appRoot.getAttribute('data-page-size') || 10),
    };

    const setStatus = (text, mode = 'loading') => {
        status.className = mode === 'default' ? 'status' : `status status--${mode}`;
        status.textContent = text;
    };

    const escapeHtml = (value) => String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');

    const renderCard = (card) => {
        const media = card.image
            ? `
                <div class="card__media">
                    <div class="card__media-skeleton" aria-hidden="true"></div>
                    <img class="card__media-img" data-skeleton-image src="${escapeHtml(card.image)}" alt="${escapeHtml(card.title)}" loading="lazy">
                </div>
            `
            : '<div class="card__media"></div>';

        const meta = Array.isArray(card.meta)
            ? `<ul class="card__meta">${card.meta.map((item) => `<li><b>${escapeHtml(item.label)}:</b> ${escapeHtml(item.value)}</li>`).join('')}</ul>`
            : '';

        const action = card.action && card.action.url
            ? `<a class="card__action" href="${escapeHtml(card.action.url)}">${escapeHtml(card.action.label || 'Abrir')}</a>`
            : '';

        return `
            <article class="card">
                ${media}
                <div class="card__body">
                    <h3 class="card__title">${escapeHtml(card.title)}</h3>
                    <p class="card__text">${escapeHtml(card.description || 'Sem descrição disponível para este item.')}</p>
                    ${meta}
                    ${action}
                </div>
            </article>
        `;
    };

    const renderSkeletonCards = (count = 10) => {
        const total = Math.max(1, count);
        const skeletons = Array.from({ length: total }).map(() => `
            <article class="card card--skeleton" aria-hidden="true">
                <div class="card__media">
                    <div class="skeleton skeleton--media"></div>
                </div>
                <div class="card__body">
                    <div class="skeleton skeleton--title"></div>
                    <div class="skeleton skeleton--line"></div>
                    <div class="skeleton skeleton--line short"></div>
                </div>
            </article>
        `);

        grid.innerHTML = skeletons.join('');
    };

    const renderEmptyState = ({ titleText, descriptionText }) => {
        grid.innerHTML = `
            <section class="empty-state" role="status" aria-live="polite">
                <div class="empty-state__icon" aria-hidden="true">
                    <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" focusable="false">
                        <circle cx="24" cy="24" r="20" fill="none" stroke="currentColor" stroke-width="2"></circle>
                        <path d="M16 20h16M16 26h11M16 32h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                </div>
                <h3>${escapeHtml(titleText)}</h3>
                <p>${escapeHtml(descriptionText)}</p>
            </section>
        `;
    };

    const hydrateCardImages = () => {
        grid.querySelectorAll('img[data-skeleton-image]').forEach((img) => {
            const media = img.closest('.card__media');
            if (!media) {
                return;
            }

            const markLoaded = () => {
                media.classList.add('is-loaded');
            };

            const markError = () => {
                media.classList.add('is-error');
            };

            if (img.complete && img.naturalWidth > 0) {
                markLoaded();
                return;
            }

            img.addEventListener('load', markLoaded, { once: true });
            img.addEventListener('error', markError, { once: true });
        });
    };

    const renderPagination = () => {
        pagination.innerHTML = '';

        const prev = document.createElement('button');
        prev.textContent = 'Anterior';
        prev.disabled = state.page <= 1;
        prev.addEventListener('click', () => loadData(state.type, state.page - 1));

        const next = document.createElement('button');
        next.textContent = 'Próxima';
        next.disabled = state.page >= state.totalPages;
        next.addEventListener('click', () => loadData(state.type, state.page + 1));

        const current = document.createElement('span');
        current.textContent = `Página ${state.page} de ${state.totalPages}`;

        pagination.append(prev, current, next);
    };

    const setActiveChip = () => {
        if (!controls) {
            return;
        }
        controls.querySelectorAll('button[data-type]').forEach((btn) => {
            const active = btn.getAttribute('data-type') === state.type;
            btn.classList.toggle('is-active', active);
        });
    };

    const loadData = async (type, page = 1) => {
        state.type = type;
        state.page = page;
        setActiveChip();
        setStatus('Carregando dados da coleção...', 'loading');
        renderSkeletonCards(state.pageSize);
        pagination.innerHTML = '';

        try {
            const response = await fetch(`api/collection.php?type=${encodeURIComponent(type)}&page=${encodeURIComponent(page)}`);
            const payload = await response.json();

            if (!payload.ok) {
                throw new Error(payload.error || 'Não foi possível obter os dados da coleção.');
            }

            if (title) {
                title.textContent = payload.title;
            }
            if (description) {
                description.textContent = payload.description;
            }
            state.page = payload.page;
            state.totalPages = payload.totalPages;

            if (!Array.isArray(payload.cards) || payload.cards.length === 0) {
                setStatus('Nenhum item disponível para esta coleção.', 'empty');
                renderEmptyState({
                    titleText: 'Nada para exibir no momento',
                    descriptionText: 'Tente selecionar outra coleção ou atualizar a página.',
                });
                return;
            }

            setStatus(`Exibindo ${payload.cards.length} itens nesta página.`, 'default');
            grid.innerHTML = payload.cards.map(renderCard).join('');
            hydrateCardImages();
            renderPagination();
        } catch (error) {
            setStatus(error.message || 'Erro inesperado ao carregar os dados da coleção.', 'error');
            renderEmptyState({
                titleText: 'Não foi possível carregar a coleção',
                descriptionText: 'A API do Valorant está indisponível ou retornou uma resposta inválida. Tente novamente em instantes.',
            });
        }
    };

    if (controls) {
        controls.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLButtonElement)) {
                return;
            }
            const nextType = target.getAttribute('data-type');
            if (!nextType) {
                return;
            }
            loadData(nextType, 1);
        });
    }

    loadData(state.type, 1);
})();
