package com.smartcodegen.backend.security;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.web.SecurityFilterChain;

@Configuration
@EnableWebSecurity
public class SecurityConfig {

    @Bean
    public SecurityFilterChain securityFilterChain(HttpSecurity http) throws Exception {
        http
            .csrf(csrf -> csrf.disable()) // CSRF kikapcsolása (fejlesztési célokra OK)
            .authorizeHttpRequests(authorize -> authorize
                .requestMatchers("/api/generate").permitAll() // Engedélyezi a /api/generate hívást hitelesítés nélkül
                .anyRequest().authenticated() // Minden más kéréshez hitelesítés szükséges
            );
        return http.build();
    }
}