<template>
    <div class="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav">
                <li class="nav-item">
                    <router-link :to="'/dashboard'" class="nav-link">
                        <i class="icon-speedometer"></i> Dashboard
                        <span class="badge badge-info">NEW</span>
                    </router-link>
                </li>
                <li class="nav-title">
                    Extras
                </li>

                <router-link tag="li" class="nav-item nav-dropdown" :to="{ path: '/pages'}" disabled>
                    <div class="nav-link nav-dropdown-toggle" @click="handleClick">
                        <i class="icon-star"></i> Pages</div>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <router-link :to="'/pages/login'" class="nav-link" exact>
                                <i class="icon-star"></i> Login</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="'/pages/register'" class="nav-link" exact>
                                <i class="icon-star"></i> Register</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="'/pages/404'" class="nav-link" exact>
                                <i class="icon-star"></i> Error 404</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="'/pages/500'" class="nav-link" exact>
                                <i class="icon-star"></i> Error 500</router-link>
                        </li>
                    </ul>
                </router-link>

                <router-link tag="li" class="nav-item nav-dropdown" :to="{ path: '/master'}" disabled>
                    <div class="nav-link nav-dropdown-toggle" @click="handleClick">
                        <i class="icon-user"></i> Tentang Aku</div>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <router-link :to="'/master/mititi'" class="nav-link" exact>
                                <i class="icon-puzzle"></i> Materi Tips n Trik</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="'/master/kalpen'" class="nav-link" exact>
                                <i class="icon-calendar"></i> Kalpen</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="'/master/smartebook'" class="nav-link" exact>
                                <i class="icon-notebook"></i> Smart Ebook</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="'/master/smartexercise'" class="nav-link" exact>
                                <i class="icon-hourglass"></i> Smart Exercise</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="'/master/pikse'" class="nav-link" exact>
                                <i class="icon-question"></i> Pikse</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="'/master/rumus'" class="nav-link" exact>
                                <i class="icon-calculator"></i> Rumus</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="'/master/bakmi'" class="nav-link" exact>
                                <i class="icon-book-open"></i> Pembahasan Materi</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="'/master/ketela'" class="nav-link" exact>
                                <i class="icon-control-play"></i> Video Pembelajaran</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="'/master/sekolah'" class="nav-link" exact>
                                <i class="icon-graduation"></i> Asal Sekolah</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="'/master/banner'" class="nav-link" exact>
                                <i class="icon-picture"></i> Banner</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="'/master/pesan'" class="nav-link" exact>
                                <i class="icon-envelope"></i> Pesan</router-link>
                        </li>
                    </ul>
                </router-link>

            </ul>
        </nav>
    </div>
</template>
<script>

export default {
    name: 'sidebar',
    methods: {
        handleClick(e) {
            e.preventDefault()
            e.target.parentElement.classList.toggle('open')
        }
    }
}
</script>

<style lang="css">
.nav-link {
    cursor: pointer;
}
</style>
