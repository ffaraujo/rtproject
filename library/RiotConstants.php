<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RiotConstants
 *
 * @author fabio.araujo
 */
class RiotConstants {

    public static $queues = array(
        'CUSTOM',
        'NORMAL_5x5_BLIND', 'NORMAL_5x5_DRAFT',
        'ODIN_5x5_BLIND', 'ODIN_5x5_DRAFT',
        'NORMAL_3x3', 'GROUP_FINDER_5x5', 'ARAM_5x5',
        'BOT_5x5', 'BOT_5x5_INTRO', 'BOT_5x5_BEGINNER', 'BOT_5x5_INTERMEDIATE',
        'BOT_ODIN_5x5', 'BOT_TT_3x3',
        'RANKED_SOLO_5x5', 'RANKED_PREMADE_3x3', 'RANKED_PREMADE_5x5', 'RANKED_TEAM_3x3',
        'RANKED_TEAM_5x5',
        'ONEFORALL_5x5', 'FIRSTBLOOD_1x1', 'FIRSTBLOOD_2x2', 'SR_6x6', 'URF_5x5',
        'BOT_URF_5x5', 'ASCENSION_5x5', 'HEXAKILL', 'KING_PORO_5x5',
        'NIGHTMARE_BOT_5x5_RANK1', 'NIGHTMARE_BOT_5x5_RANK2', 'NIGHTMARE_BOT_5x5_RANK5',
    );
    public static $maps = array(
        1 => array("Summoner's Rift", "Original Summer Variant"),
        2 => array("Summoner's Rift", "Original Autumn Variant"),
        3 => array("The Proving Grounds", "Tutorial Map"),
        4 => array("Twisted Treeline", "Original Version"),
        8 => array("The Crystal Scar", "Dominion Map"),
        10 => array("Twisted Treeline", "Current Version"),
        11 => array("Summoner's Rift", "Current Version"),
        12 => array("Howling Abyss", "ARAM Map"),
    );
    public static $gameMods = array(
        'CLASSIC', 'ODIN', 'ARAM', 'TUTORIAL', 'ONEFORALL',
        'ASCENSION', 'FIRSTBLOOD', 'KINGPORO',
    );
    public static $gameTypes = array(
        'CUSTOM_GAME', 'TUTORIAL_GAME', 'MATCHED_GAME',
    );
    public static $subTypes = array(
        'NONE', 'NORMAL', 'NORMAL_3x3', 'ODIN_UNRANKED', 'ARAM_UNRANKED_5x5',
        'BOT', 'BOT_3x3', 'RANKED_SOLO_5x5', 'RANKED_TEAM_3x3', 'RANKED_TEAM_5x5',
        'CAP_5x5', 'ONEFORALL_5x5', 'FIRSTBLOOD_1x1', 'FIRSTBLOOD_2x2', 'SR_6x6', 
        'URF', 'URF_BOT', 'NIGHTMARE_BOT', 'ASCENSION', 'HEXAKILL',
        'KING_PORO',
    );
    public static $playerStatSummaryTypes = array(
        'Unranked', 'Unranked3x3', 'OdinUnranked', 'AramUnranked5x5', 'CoopVsAI',
        'CoopVsAI3x3', 'RankedSolo5x5', 'RankedTeam3x3', 'RankedTeam5x5', 'OneForAll5x5',
        'FirstBlood1x1', 'FirstBlood2x2', 'SummonersRift6x6', 'CAP5x5', 'URF',
        'URFBots', 'NightmareBot', 'Ascension', 'Hexakill', 'KingPoro',
    );
    public static $languages = array(
        'en_US', 'es_AR', 'pt_BR', 'pt_PT', 'fr_FR', 'de_DE', 'it_IT',
    );
    public static $regionalEndpoints = array(
        'BR' => 'br.api.pvp.net',
        'EUNE' => 'eune.api.pvp.net',
        'EUW' => 'euw.api.pvp.net',
        'KR' => 'kr.api.pvp.net',
        'LAS' => 'las.api.pvp.net',
        'LAN' => 'lan.api.pvp.net',
        'NA' => 'na.api.pvp.net',
        'OCE' => 'oce.api.pvp.net',
        'TR' => 'tr.api.pvp.net',
        'RU' => 'ru.api.pvp.net',
        'GLOBAL' => 'global.api.pvp.net',
    );

}
