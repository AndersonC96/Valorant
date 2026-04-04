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
            ? `<div class="card__media"><img src="${escapeHtml(card.image)}" alt="${escapeHtml(card.title)}"></div>`
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
        grid.innerHTML = '';
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
                return;
            }

            setStatus(`Exibindo ${payload.cards.length} itens nesta página.`, 'default');
            grid.innerHTML = payload.cards.map(renderCard).join('');
            renderPagination();
        } catch (error) {
            setStatus(error.message || 'Erro inesperado ao carregar os dados da coleção.', 'error');
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
