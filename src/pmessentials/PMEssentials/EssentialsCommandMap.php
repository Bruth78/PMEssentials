<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\command\FeedCommand;
use pmessentials\PMEssentials\command\FlyCommand;
use pmessentials\PMEssentials\command\GameModeCommand;
use pmessentials\PMEssentials\command\HealCommand;
use pmessentials\PMEssentials\command\ICommand;
use pmessentials\PMEssentials\command\NickCommand;
use pmessentials\PMEssentials\command\PingCommand;
use pmessentials\PMEssentials\command\PowertoolCommand;
use pmessentials\PMEssentials\command\RealNameCommand;
use pmessentials\PMEssentials\command\SimpleCommand;
use pmessentials\PMEssentials\command\SizeCommand;
use pmessentials\PMEssentials\command\UsageCommand;
use pmessentials\PMEssentials\command\VanishCommand;
use pmessentials\PMEssentials\listener\PowertoolListener;
use pmessentials\PMEssentials\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandMap;
use pocketmine\command\PluginCommand;
use pocketmine\command\SimpleCommandMap;
use pocketmine\utils\TextFormat;

class EssentialsCommandMap {

    private $plugin;
    private static $instance;
    private static $api;

    private $commands = [];

    public static function getInstance() : EssentialsCommandMap{
        self::$api = API::getAPI();
        if (self::$instance == null)
        {
            self::$instance = new EssentialsCommandMap(self::$api->getPlugin());
        }

        return self::$instance;
    }

    private function __construct(Main $plugin){
        $this->plugin = $plugin;
        $this->setDefaultCommands();
    }

    private function setDefaultCommands() : void{
        try{
            $nick = new SimpleCommand("nick", $this->plugin);
            $nick->setExecutor(new NickCommand());
            $nick->setDescription("change your nickname");
            $nick->setPermission("pmessentials.nick");
            $nick->setAliases(["name", "nickname"]);
            $nick->setUsage("/nick [nick] [player]");
            $this->register($nick);
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: nick"));
            $this->error($e);
        }


        try{
            $heal = new SimpleCommand("heal", $this->plugin);
            $heal->setExecutor(new HealCommand());
            $heal->setDescription("heal a player");
            $heal->setPermission("pmessentials.heal");
            $heal->setUsage("/heal [player]");
            $this->register($heal);
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: heal"));
            $this->error($e);
        }

        try{
            $feed = new SimpleCommand("feed", $this->plugin);
            $feed->setExecutor(new FeedCommand());
            $feed->setDescription("feed a player");
            $feed->setPermission("pmessentials.feed");
            $feed->setUsage("/feed [player]");
            $this->register($feed);
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: feed"));
            $this->error($e);
        }

        try{
            $pmgm = $this->plugin->getServer()->getCommandMap()->getCommand("gamemode");
            $this->plugin->getServer()->getCommandMap()->unregister($pmgm);

            $gm = new SimpleCommand("gamemode", $this->plugin);
            $gm->setExecutor(new GameModeCommand());
            $gm->setDescription("change your gamemode");
            $gm->setPermission("pmessentials.gamemode");
            $gm->setAliases(["gm", "gms", "gmc", "gma", "gmspc", "gmv"]);
            $gm->setUsage("/gamemode <mode> [player]");
            $this->register($gm);
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: gamemode"));
            $this->error($e);
        }

        try{
            $i = new SimpleCommand("i", $this->plugin);
            $i->setExecutor(new ICommand());
            $i->setDescription("gives you an item");
            $i->setPermission("pmessentials.i");
            $i->setUsage("/i <item>:[meta] [count]");
            $this->register($i);
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: i"));
            $this->error($e);
        }

        try{
            $size = new SimpleCommand("size", $this->plugin);
            $size->setExecutor(new SizeCommand());
            $size->setDescription("resize a player");
            $size->setPermission("pmessentials.size");
            $size->setAliases(["scale"]);
            $size->setUsage("/size [size] [player]");
            $this->register($size);
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: size"));
            $this->error($e);
        }

        try{
            $realname = new SimpleCommand("realname", $this->plugin);
            $realname->setExecutor(new RealNameCommand());
            $realname->setDescription("view someone's real name");
            $realname->setPermission("pmessentials.realname");
            $realname->setUsage("/realname <nick>");
            $this->register($realname);
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: realname"));
            $this->error($e);
        }

        try{
            $usage = new SimpleCommand("usage", $this->plugin);
            $usage->setExecutor(new UsageCommand());
            $usage->setDescription("Check a command's usage");
            $usage->setPermission("pmessentials.usage");
            $usage->setAliases(["howtouse"]);
            $usage->setUsage("/usage <command>");
            $this->register($usage);
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: usage"));
            $this->error($e);
        }

        try{
            $pt = new SimpleCommand("powertool", $this->plugin);
            $pt->setExecutor(new PowertoolCommand());
            $pt->setDescription("Assign a command to an item");
            $pt->setPermission("pmessentials.powertool.set");
            $pt->setAliases(["pt"]);
            $pt->setUsage("/powertool <command>");
            $this->register($pt);
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: powertool"));
            $this->error($e);
        }

        try{
            $ping = new SimpleCommand("ping", $this->plugin);
            $ping->setExecutor(new PingCommand());
            $ping->setDescription("Pong!");
            $ping->setPermission("pmessentials.ping");
            $ping->setUsage("/ping");
            $this->register($ping);
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: ping"));
            $this->error($e);
        }

        try{
            $fly = new SimpleCommand("fly", $this->plugin);
            $fly->setExecutor(new FlyCommand());
            $fly->setDescription("enable/disable flight");
            $fly->setPermission("pmessentials.fly");
            $fly->setUsage("/fly [player]");
            $this->register($fly);
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: fly"));
            $this->error($e);
        }

        try{
            $v = new SimpleCommand("vanish", $this->plugin);
            $v->setExecutor(new VanishCommand());
            $v->setDescription("enable/disable vanish");
            $v->setPermission("pmessentials.vanish");
            $v->setAliases(["v", "invis"]);
            $v->setUsage("/vanish [player]");
            $this->register($v);
        }catch (\Error $e){
            $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("could not register command: vanish"));
            $this->error($e);
        }
    }

    public function register(Command $command) : void{
        $this->commands[$command->getName()] = $command;
        $this->plugin->getServer()->getCommandMap()->register("pmessentials", $command, $command->getName());
    }

    public function unregister(Command $command) : bool{
        foreach($this->commands as $lbl => $cmd){
            if($cmd === $command){
                unset($this->commands[$lbl]);
            }
        }

        $this->plugin->getServer()->getCommandMap()->unregister($command);

        return true;
    }

    public function getCommand(string $name) : Command{
        return $this->commands[$name] ?? null;
    }

    public function getCommands() : array{
        return $this->commands;
    }

    private function error(\Error $e) : void{
        $this->plugin->getServer()->getLogger()->error(TextFormat::colorize("&cError ".$e->getCode().": ".$e->getMessage()." on line ".$e->getLine()." in file ".$e->getFile()));
    }
}